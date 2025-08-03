<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrderFeedback;

class OrderFeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $feedback = OrderFeedback::where('order_id', $request->order_id)->first();
        if (!$feedback) {
            return response()->json(['exists' => false], 200);
        }
        return response()->json(['exists' => true, 'feedback' => $feedback], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id|unique:order_feedback,order_id',
            'quality_score' => 'required|integer|min:1|max:3',
            'compliance_score' => 'required|integer|min:1|max:3',
            'attention_score' => 'required|integer|min:1|max:3',
        ]);

        $feedback = OrderFeedback::create($validated);

        return response()->json([
            "success" => true,
            'message' => 'Feedback saved',
            'data' => $feedback
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

//    public function reportQuality(Request $request)
//    {
//        $query = OrderFeedback::with('order');
//
//        if ($request->has('start') && $request->has('end')) {
//            $query->whereHas('order', function ($q) use ($request) {
//                $q->whereBetween('delivery_date', [$request->start, $request->end]);
//            });
//        }
//
//        $feedbacks = $query->get();
//
//        // Inicializar contadores
//        $result = [
//            'quality_score' => [],
//            'compliance_score' => [],
//            'attention_score' => []
//        ];
//
//        foreach ($feedbacks as $feedback) {
//            foreach (['quality_score', 'compliance_score', 'attention_score'] as $type) {
//                $value = $feedback->$type;
//                if ($value !== null) {
//                    $key = 'question_' . $value;
//
//                    if (!isset($result[$type][$key])) {
//                        $result[$type][$key] = 0;
//                    }
//
//                    $result[$type][$key]++;
//                }
//            }
//        }
//
//        return response()->json($result);
//    }
    public function reportQuality(Request $request)
    {
        $query = OrderFeedback::with('order');

        if ($request->has('start') || $request->has('end')) {
            $query->whereHas('order', function ($q) use ($request) {
                if ($request->has('start')) {
                    $q->where('issue_date', '>=', $request->start);
                }
                if ($request->has('end')) {
                    $q->where('issue_date', '<=', $request->end);
                }
            });
        }

        $feedbacks = $query->get();

        // Inicializar contadores por question_1, question_2, etc.
        $result = [
            'quality_score' => [],
            'compliance_score' => [],
            'attention_score' => []
        ];

        // Variables para promedios
        $sum = [
            'quality_score' => 0,
            'compliance_score' => 0,
            'attention_score' => 0
        ];
        $count = [
            'quality_score' => 0,
            'compliance_score' => 0,
            'attention_score' => 0
        ];

        foreach ($feedbacks as $feedback) {
            foreach (['quality_score', 'compliance_score', 'attention_score'] as $type) {
                $value = $feedback->$type;

                // Para contador por pregunta
                if ($value !== null) {
                    $key = 'question_' . $value;

                    if (!isset($result[$type][$key])) {
                        $result[$type][$key] = 0;
                    }

                    $result[$type][$key]++;

                    // Para promedio
                    $sum[$type] += $value;
                    $count[$type]++;
                }
            }
        }

        // Calcular promedios
        $generalAnalysis = [];
        foreach ($sum as $type => $total) {
            $generalAnalysis[$type] = $count[$type] > 0 ? round($total / $count[$type], 2) : 0;
        }

        return response()->json([
            'scores' => $result,
            'general_analysis' => $generalAnalysis
        ]);
    }

}
