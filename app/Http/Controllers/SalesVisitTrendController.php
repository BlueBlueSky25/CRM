<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesVisitTrendController extends Controller
{
    /**
     * Get visit trend data based on period
     * Periods: daily, weekly, monthly, yearly, custom
     */
    public function getVisitTrend(Request $request)
    {
        try {
            $period = $request->input('period', 'monthly');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            
            // If custom date range provided
            if ($startDate && $endDate) {
                return $this->getCustomRangeTrend($startDate, $endDate);
            }
            
            $limit = $this->getLimitByPeriod($period);
            
            switch ($period) {
                case 'daily':
                    return $this->getDailyTrend($limit);
                case 'weekly':
                    return $this->getWeeklyTrend($limit);
                case 'monthly':
                    return $this->getMonthlyTrend($limit);
                case 'yearly':
                    return $this->getYearlyTrend($limit);
                default:
                    return $this->getMonthlyTrend($limit);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get custom date range trend (auto-detect best grouping)
     */
    private function getCustomRangeTrend($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $daysDiff = $start->diffInDays($end);
        
        // Auto-detect best grouping based on range
        if ($daysDiff <= 31) {
            // <= 1 month: Group by day
            return $this->getCustomDailyTrend($start, $end);
        } elseif ($daysDiff <= 90) {
            // <= 3 months: Group by week
            return $this->getCustomWeeklyTrend($start, $end);
        } elseif ($daysDiff <= 730) {
            // <= 2 years: Group by month
            return $this->getCustomMonthlyTrend($start, $end);
        } else {
            // > 2 years: Group by year
            return $this->getCustomYearlyTrend($start, $end);
        }
    }

    /**
     * Custom daily trend
     */
    private function getCustomDailyTrend($startDate, $endDate)
    {
        $visits = DB::table('sales_visits')
            ->select(
                DB::raw('DATE(visit_date) as date'),
                DB::raw('COUNT(*) as visit_count')
            )
            ->whereBetween('visit_date', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(visit_date)'))
            ->orderBy('date', 'asc')
            ->get();

        $allDates = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $visit = $visits->firstWhere('date', $dateStr);
            
            $allDates[] = [
                'label' => $currentDate->format('d M'),
                'date' => $dateStr,
                'count' => $visit ? $visit->visit_count : 0
            ];
            
            $currentDate->addDay();
        }

        $cumulative = 0;
        $cumulativeData = array_map(function($item) use (&$cumulative) {
            $cumulative += $item['count'];
            return $cumulative;
        }, $allDates);

        return response()->json([
            'success' => true,
            'period' => 'custom-daily',
            'data' => [
                'labels' => array_column($allDates, 'label'),
                'visits' => array_column($allDates, 'count'),
                'cumulative' => $cumulativeData
            ],
            'stats' => [
                'total_visits' => $cumulative,
                'average_per_day' => count($allDates) > 0 ? round($cumulative / count($allDates), 1) : 0,
                'period_label' => $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y')
            ]
        ]);
    }

    /**
     * Custom weekly trend
     */
    private function getCustomWeeklyTrend($startDate, $endDate)
    {
        $visits = DB::table('sales_visits')
            ->select(
                DB::raw('EXTRACT(YEAR FROM visit_date) as year'),
                DB::raw('EXTRACT(WEEK FROM visit_date) as week'),
                DB::raw('COUNT(*) as visit_count')
            )
            ->whereBetween('visit_date', [$startDate, $endDate])
            ->groupBy(DB::raw('EXTRACT(YEAR FROM visit_date)'), DB::raw('EXTRACT(WEEK FROM visit_date)'))
            ->orderBy('year', 'asc')
            ->orderBy('week', 'asc')
            ->get();

        $allWeeks = [];
        $currentDate = $startDate->copy()->startOfWeek();
        
        while ($currentDate <= $endDate) {
            $year = $currentDate->year;
            $week = $currentDate->week;
            
            $visit = $visits->where('year', $year)->where('week', $week)->first();
            
            $allWeeks[] = [
                'label' => 'W' . $week . ' ' . $currentDate->format('M'),
                'year' => $year,
                'week' => $week,
                'count' => $visit ? $visit->visit_count : 0
            ];
            
            $currentDate->addWeek();
        }

        $cumulative = 0;
        $cumulativeData = array_map(function($item) use (&$cumulative) {
            $cumulative += $item['count'];
            return $cumulative;
        }, $allWeeks);

        return response()->json([
            'success' => true,
            'period' => 'custom-weekly',
            'data' => [
                'labels' => array_column($allWeeks, 'label'),
                'visits' => array_column($allWeeks, 'count'),
                'cumulative' => $cumulativeData
            ],
            'stats' => [
                'total_visits' => $cumulative,
                'average_per_week' => count($allWeeks) > 0 ? round($cumulative / count($allWeeks), 1) : 0,
                'period_label' => $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y')
            ]
        ]);
    }

    /**
     * Custom monthly trend
     */
    private function getCustomMonthlyTrend($startDate, $endDate)
    {
        $visits = DB::table('sales_visits')
            ->select(
                DB::raw('EXTRACT(YEAR FROM visit_date) as year'),
                DB::raw('EXTRACT(MONTH FROM visit_date) as month'),
                DB::raw('COUNT(*) as visit_count')
            )
            ->whereBetween('visit_date', [$startDate, $endDate])
            ->groupBy(DB::raw('EXTRACT(YEAR FROM visit_date)'), DB::raw('EXTRACT(MONTH FROM visit_date)'))
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $allMonths = [];
        $currentDate = $startDate->copy()->startOfMonth();
        
        while ($currentDate <= $endDate) {
            $year = $currentDate->year;
            $month = $currentDate->month;
            
            $visit = $visits->where('year', $year)->where('month', $month)->first();
            
            $allMonths[] = [
                'label' => $currentDate->format('M Y'),
                'year' => $year,
                'month' => $month,
                'count' => $visit ? $visit->visit_count : 0
            ];
            
            $currentDate->addMonth();
        }

        $cumulative = 0;
        $cumulativeData = array_map(function($item) use (&$cumulative) {
            $cumulative += $item['count'];
            return $cumulative;
        }, $allMonths);

        return response()->json([
            'success' => true,
            'period' => 'custom-monthly',
            'data' => [
                'labels' => array_column($allMonths, 'label'),
                'visits' => array_column($allMonths, 'count'),
                'cumulative' => $cumulativeData
            ],
            'stats' => [
                'total_visits' => $cumulative,
                'average_per_month' => count($allMonths) > 0 ? round($cumulative / count($allMonths), 1) : 0,
                'period_label' => $startDate->format('M Y') . ' - ' . $endDate->format('M Y')
            ]
        ]);
    }

    /**
     * Custom yearly trend
     */
    private function getCustomYearlyTrend($startDate, $endDate)
    {
        $startYear = $startDate->year;
        $endYear = $endDate->year;

        $visits = DB::table('sales_visits')
            ->select(
                DB::raw('EXTRACT(YEAR FROM visit_date) as year'),
                DB::raw('COUNT(*) as visit_count')
            )
            ->whereRaw('EXTRACT(YEAR FROM visit_date) >= ?', [$startYear])
            ->whereRaw('EXTRACT(YEAR FROM visit_date) <= ?', [$endYear])
            ->groupBy(DB::raw('EXTRACT(YEAR FROM visit_date)'))
            ->orderBy('year', 'asc')
            ->get();

        $allYears = [];
        
        for ($year = $startYear; $year <= $endYear; $year++) {
            $visit = $visits->where('year', $year)->first();
            
            $allYears[] = [
                'label' => (string)$year,
                'year' => $year,
                'count' => $visit ? $visit->visit_count : 0
            ];
        }

        $cumulative = 0;
        $cumulativeData = array_map(function($item) use (&$cumulative) {
            $cumulative += $item['count'];
            return $cumulative;
        }, $allYears);

        return response()->json([
            'success' => true,
            'period' => 'custom-yearly',
            'data' => [
                'labels' => array_column($allYears, 'label'),
                'visits' => array_column($allYears, 'count'),
                'cumulative' => $cumulativeData
            ],
            'stats' => [
                'total_visits' => $cumulative,
                'average_per_year' => count($allYears) > 0 ? round($cumulative / count($allYears), 1) : 0,
                'period_label' => $startYear . ' - ' . $endYear
            ]
        ]);
    }

    /**
     * Get daily visit trend (last 30 days)
     */
    private function getDailyTrend($limit = 30)
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays($limit);

        $visits = DB::table('sales_visits')
            ->select(
                DB::raw('DATE(visit_date) as date'),
                DB::raw('COUNT(*) as visit_count')
            )
            ->whereBetween('visit_date', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(visit_date)'))
            ->orderBy('date', 'asc')
            ->get();

        // Fill missing dates with 0
        $allDates = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $visit = $visits->firstWhere('date', $dateStr);
            
            $allDates[] = [
                'label' => $currentDate->format('d M'),
                'date' => $dateStr,
                'count' => $visit ? $visit->visit_count : 0
            ];
            
            $currentDate->addDay();
        }

        // Calculate cumulative
        $cumulative = 0;
        $cumulativeData = array_map(function($item) use (&$cumulative) {
            $cumulative += $item['count'];
            return $cumulative;
        }, $allDates);

        return response()->json([
            'success' => true,
            'period' => 'daily',
            'data' => [
                'labels' => array_column($allDates, 'label'),
                'visits' => array_column($allDates, 'count'),
                'cumulative' => $cumulativeData
            ],
            'stats' => [
                'total_visits' => $cumulative,
                'average_per_day' => count($allDates) > 0 ? round($cumulative / count($allDates), 1) : 0,
                'period_label' => $limit . ' Hari Terakhir'
            ]
        ]);
    }

    /**
     * Get weekly visit trend (last 12 weeks)
     */
    private function getWeeklyTrend($limit = 12)
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subWeeks($limit);

        $visits = DB::table('sales_visits')
            ->select(
                DB::raw('EXTRACT(YEAR FROM visit_date) as year'),
                DB::raw('EXTRACT(WEEK FROM visit_date) as week'),
                DB::raw('COUNT(*) as visit_count')
            )
            ->whereBetween('visit_date', [$startDate, $endDate])
            ->groupBy(DB::raw('EXTRACT(YEAR FROM visit_date)'), DB::raw('EXTRACT(WEEK FROM visit_date)'))
            ->orderBy('year', 'asc')
            ->orderBy('week', 'asc')
            ->get();

        // Generate all weeks
        $allWeeks = [];
        $currentDate = $startDate->copy()->startOfWeek();
        
        while ($currentDate <= $endDate) {
            $year = $currentDate->year;
            $week = $currentDate->week;
            
            $visit = $visits->where('year', $year)->where('week', $week)->first();
            
            $allWeeks[] = [
                'label' => 'W' . $week,
                'year' => $year,
                'week' => $week,
                'count' => $visit ? $visit->visit_count : 0
            ];
            
            $currentDate->addWeek();
        }

        // Calculate cumulative
        $cumulative = 0;
        $cumulativeData = array_map(function($item) use (&$cumulative) {
            $cumulative += $item['count'];
            return $cumulative;
        }, $allWeeks);

        return response()->json([
            'success' => true,
            'period' => 'weekly',
            'data' => [
                'labels' => array_column($allWeeks, 'label'),
                'visits' => array_column($allWeeks, 'count'),
                'cumulative' => $cumulativeData
            ],
            'stats' => [
                'total_visits' => $cumulative,
                'average_per_week' => count($allWeeks) > 0 ? round($cumulative / count($allWeeks), 1) : 0,
                'period_label' => $limit . ' Minggu Terakhir'
            ]
        ]);
    }

    /**
     * Get monthly visit trend (last 12 months)
     */
    private function getMonthlyTrend($limit = 12)
    {
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subMonths($limit);

        $visits = DB::table('sales_visits')
            ->select(
                DB::raw('EXTRACT(YEAR FROM visit_date) as year'),
                DB::raw('EXTRACT(MONTH FROM visit_date) as month'),
                DB::raw('COUNT(*) as visit_count')
            )
            ->whereBetween('visit_date', [$startDate, $endDate])
            ->groupBy(DB::raw('EXTRACT(YEAR FROM visit_date)'), DB::raw('EXTRACT(MONTH FROM visit_date)'))
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Generate all months
        $allMonths = [];
        $currentDate = $startDate->copy()->startOfMonth();
        
        while ($currentDate <= $endDate) {
            $year = $currentDate->year;
            $month = $currentDate->month;
            
            $visit = $visits->where('year', $year)->where('month', $month)->first();
            
            $allMonths[] = [
                'label' => $currentDate->format('M Y'),
                'year' => $year,
                'month' => $month,
                'count' => $visit ? $visit->visit_count : 0
            ];
            
            $currentDate->addMonth();
        }

        // Calculate cumulative
        $cumulative = 0;
        $cumulativeData = array_map(function($item) use (&$cumulative) {
            $cumulative += $item['count'];
            return $cumulative;
        }, $allMonths);

        return response()->json([
            'success' => true,
            'period' => 'monthly',
            'data' => [
                'labels' => array_column($allMonths, 'label'),
                'visits' => array_column($allMonths, 'count'),
                'cumulative' => $cumulativeData
            ],
            'stats' => [
                'total_visits' => $cumulative,
                'average_per_month' => count($allMonths) > 0 ? round($cumulative / count($allMonths), 1) : 0,
                'period_label' => $limit . ' Bulan Terakhir'
            ]
        ]);
    }

    /**
     * Get yearly visit trend (last 5 years)
     */
    private function getYearlyTrend($limit = 5)
    {
        $endYear = Carbon::now()->year;
        $startYear = $endYear - $limit + 1;

        $visits = DB::table('sales_visits')
            ->select(
                DB::raw('EXTRACT(YEAR FROM visit_date) as year'),
                DB::raw('COUNT(*) as visit_count')
            )
            ->whereRaw('EXTRACT(YEAR FROM visit_date) >= ?', [$startYear])
            ->groupBy(DB::raw('EXTRACT(YEAR FROM visit_date)'))
            ->orderBy('year', 'asc')
            ->get();

        // Generate all years
        $allYears = [];
        
        for ($year = $startYear; $year <= $endYear; $year++) {
            $visit = $visits->where('year', $year)->first();
            
            $allYears[] = [
                'label' => (string)$year,
                'year' => $year,
                'count' => $visit ? $visit->visit_count : 0
            ];
        }

        // Calculate cumulative
        $cumulative = 0;
        $cumulativeData = array_map(function($item) use (&$cumulative) {
            $cumulative += $item['count'];
            return $cumulative;
        }, $allYears);

        return response()->json([
            'success' => true,
            'period' => 'yearly',
            'data' => [
                'labels' => array_column($allYears, 'label'),
                'visits' => array_column($allYears, 'count'),
                'cumulative' => $cumulativeData
            ],
            'stats' => [
                'total_visits' => $cumulative,
                'average_per_year' => count($allYears) > 0 ? round($cumulative / count($allYears), 1) : 0,
                'period_label' => $limit . ' Tahun Terakhir'
            ]
        ]);
    }

    /**
     * Get limit based on period
     */
    private function getLimitByPeriod($period)
    {
        switch ($period) {
            case 'daily':
                return 30;  // 30 days
            case 'weekly':
                return 12;  // 12 weeks
            case 'monthly':
                return 12;  // 12 months
            case 'yearly':
                return 5;   // 5 years
            default:
                return 12;
        }
    }
}