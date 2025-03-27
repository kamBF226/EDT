<?php

namespace App\Http\Controllers;

use Kreait\Firebase\Contract\Firestore;

class FirebaseTestController extends Controller
{
    protected $firestore;

    public function __construct(Firestore $firestore)
    {
        $this->firestore = $firestore;
    }

    public function test()
    {
        $database = $this->firestore->database();
        $collection = $database->collection('test_collection');
        $document = $collection->document('test_doc');
        $document->set(['message' => 'Firebase works']);

        return response()->json(['message' => 'Firebase test successful']);
    }
}
