<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Kreait\Firebase\Contract\Firestore;

class UserController extends Controller
{
    protected $auth;
    protected $firestore;

    public function __construct(Auth $auth, Firestore $firestore)
    {
        $this->auth = $auth;
        $this->firestore = $firestore;
    }





    
    public function create(Request $request)
{
    // Valider les données entrantes
    $validated = $request->validate([
        'nom' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'motDePasse' => 'required|string|min:6',
        'role' => 'required|in:enseignant,etudiant,delegue,parent',
        'filiereId' => 'required_if:role,etudiant,delegue|string',
        'promotionId' => 'required_if:role,etudiant,delegue|string',
        'specialite' => 'nullable|string',
    ]);

    try {
        // Créer l'utilisateur dans Firebase Auth
        $userProperties = [
            'email' => $validated['email'],
            'password' => $validated['motDePasse'],
        ];
        $user = $this->auth->createUser($userProperties);
        $uid = $user->uid;

        // Préparer les données pour Firestore
        $userData = [
            'id' => $uid,
            'nom' => $validated['nom'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        // Ajouter les champs spécifiques selon le rôle
        if ($validated['role'] === 'etudiant' || $validated['role'] === 'delegue') {
            $userData['filiereId'] = $validated['filiereId'];
            $userData['promotionId'] = $validated['promotionId'];
        }

        if ($validated['role'] === 'enseignant' && isset($validated['specialite'])) {
            $userData['specialite'] = $validated['specialite'];
        }

        // Ajouter l'utilisateur dans Firestore
        $this->firestore->database()->collection('users')->document($uid)->set($userData);

        return response()->json(['message' => 'Utilisateur créé avec succès', 'id' => $uid], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erreur lors de la création de l’utilisateur : ' . $e->getMessage()], 500);
    }
}








public function getByEmail(Request $request)
{
    $email = $request->query('email');
    if (!$email) {
        return response()->json(['error' => 'Email requis'], 400);
    }

    try {
        $usersRef = $this->firestore->database()->collection('users');
        $query = $usersRef->where('email', '=', $email);
        $documents = $query->documents();

        foreach ($documents as $document) {
            if ($document->exists()) {
                return response()->json($document->data());
            }
        }

        return response()->json(['error' => 'Utilisateur non trouvé'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erreur lors de la récupération : ' . $e->getMessage()], 500);
    }
}










}
