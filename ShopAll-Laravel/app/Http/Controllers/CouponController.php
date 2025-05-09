<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CouponController extends Controller
{
    // Liste des coupons de test
    private $testCoupons = [
        'WELCOME10' => [
            'discount_type' => 'percentage',
            'discount_value' => 10, // 10% de réduction
            'active' => true
        ],
        'SUMMER20' => [
            'discount_type' => 'percentage',
            'discount_value' => 20, // 20% de réduction
            'active' => true
        ],
        'FLAT50' => [
            'discount_type' => 'fixed',
            'discount_value' => 50, // 50 DH de réduction
            'active' => true
        ]
    ];

    public function applyCoupon(Request $request)
    {
        $code = strtoupper($request->code);
        
        // Vérifier si le coupon existe et est valide
        if (!isset($this->testCoupons[$code]) || !$this->testCoupons[$code]['active']) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon invalide ou expiré'
            ], 404);
        }
        
        $coupon = $this->testCoupons[$code];
        
        // Calculer le montant de la réduction
        $subtotal = session()->get('cart_subtotal', 0);
        $discountAmount = 0;
        
        if ($coupon['discount_type'] === 'percentage') {
            $discountAmount = ($subtotal * $coupon['discount_value']) / 100;
        } else {
            $discountAmount = $coupon['discount_value'];
            // S'assurer que la réduction ne dépasse pas le sous-total
            if ($discountAmount > $subtotal) {
                $discountAmount = $subtotal;
            }
        }
        
        // Stocker le coupon dans la session
        session()->put('coupon', [
            'code' => $code,
            'discount_type' => $coupon['discount_type'],
            'discount_value' => $coupon['discount_value'],
            'discount_amount' => $discountAmount
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Coupon appliqué avec succès',
            'discount_amount' => $discountAmount,
            'discount_type' => $coupon['discount_type'],
            'discount_value' => $coupon['discount_value']
        ]);
    }
}