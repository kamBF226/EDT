<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Firestore;

class ResourceController extends Controller
{
    protected $firestore;

    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore;
    }

    public function createFiliere(Request $request)
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
    ]);

    try {
        $id = uniqid('filiere_');
        $filiereData = [
            'id' => $id,
            'nom' => $validated['nom']
        ];
        $this->firestore->database()->collection('filieres')->document($id)->set($filiereData);
        return response()->json(['message' => 'Filière créée avec succès', 'id' => $id], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erreur lors de la création : ' . $e->getMessage()], 500);
    }
}

public function getFilieres()
{
    try {
        $filieres = $this->firestore->database()->collection('filieres')->documents();
        $result = [];
        foreach ($filieres as $filiere) {
            if ($filiere->exists()) {
                $result[] = $filiere->data();
            }
        }
        return response()->json($result);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erreur lors de la récupération : ' . $e->getMessage()], 500);
    }
}

public function createPromotion(Request $request)
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
    ]);

    try {
        $id = uniqid('promo_');
        $promotionData = [
            'id' => $id,
            'nom' => $validated['nom']
        ];
        $this->firestore->database()->collection('promotions')->document($id)->set($promotionData);
        return response()->json(['message' => 'Promotion créée avec succès', 'id' => $id], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erreur lors de la création : ' . $e->getMessage()], 500);
    }
}

public function getPromotions()
{
    try {
        $promotions = $this->firestore->database()->collection('promotions')->documents();
        $result = [];
        foreach ($promotions as $promotion) {
            if ($promotion->exists()) {
                $result[] = $promotion->data();
            }
        }
        return response()->json($result);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erreur lors de la récupération : ' . $e->getMessage()], 500);
    }
}


public function createSalle(Request $request)
{
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'capacite' => 'required|integer|min:1',
        'disponible' => 'boolean'
    ]);

    try {
        $id = uniqid('salle_');
        $salleData = [
            'id' => $id,
            'nom' => $validated['nom'],
            'capacite' => $validated['capacite'],
            'disponible' => $validated['disponible'] ?? true
        ];
        $this->firestore->database()->collection('salles')->document($id)->set($salleData);
        return response()->json(['message' => 'Salle créée avec succès', 'id' => $id], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erreur lors de la création : ' . $e->getMessage()], 500);
    }
}

public function getSalles()
{
    try {
        $salles = $this->firestore->database()->collection('salles')->documents();
        $result = [];
        foreach ($salles as $salle) {
            if ($salle->exists()) {
                $result[] = $salle->data();
            }
        }
        return response()->json($result);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erreur lors de la récupération : ' . $e->getMessage()], 500);
    }
}


}
