<?php

namespace App\Http\Controllers;

use App\Http\Resources\TranslationResource;
use App\Jobs\GenerateTranslationExport;
use App\Models\Translation;
use App\ResponseHelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TranslationController extends Controller
{
    use ResponseHelperTrait;
    
    public function index(Request $request)
    {
        $query = Translation::query();

        if ($request->has('tag')) {
            $query->where('tag', $request->tag);
        }
        
        if ($request->has('locale')) {
            $query->where('locale', $request->locale);
        }

        if ($request->has('key')) {
            $query->where('key', 'like', '%' . $request->key . '%');
        }

        if ($request->has('content')) {
            $query->where('content', 'like', '%' . $request->content . '%');
        }
        
        if ($request->has('perPage')) {
            $perPage = $request->perPage ?? 10;
            $pageNo = $request->pageNo ?? 1;
            $cloned_query = clone $query;
            $total_records_count = $cloned_query->count();
            $data = ['total_records_count' => $total_records_count, 'per_page' => $perPage, 'page_no' => $pageNo];
            $data['records'] = $query->limit($request->perPage)->offset(($pageNo - 1) * $perPage)->get();
        }
        else{
            // $data = TranslationResource::collection($query->get());
            return $this->errorResponse('data set is too big for this request. please provide "PerPage" Param', 413);
        }

        return $this->successResponse($data, 'Translations retrieved successfully.');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'key' => 'required|string',
            'content' => 'required|string',
            'locale' => 'required|string',
            'tag' => 'nullable|string',
        ]);

        try{
            $translation = Translation::create($validatedData);
            return $this->successResponse (new TranslationResource($translation), 'Translation created successfully.', 201);
        }
        catch (\Exception $e){
            return $this->errorResponse('An error occurred while creating the translation.', 500);
        }
    }

    public function show(Translation $translation)
    {
        return $this->successResponse (new TranslationResource($translation), 'Translation Retrieved successfully.');
    }

    public function update(Request $request, Translation $translation)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'key' => 'required|string',
            'content' => 'required|string',
            'locale' => 'required|string',
            'tag' => 'nullable|string',
        ]);

        try{
            $translation->update($validatedData);
            return $this->successResponse (new TranslationResource($translation), 'Translation created successfully.', 201);
        }
        catch (\Exception $e){
            return $this->errorResponse('An error occurred while updating the translation.', 500);
        }
    }

    public function destroy(Translation $translation)
    {
        try{
            $translation->delete();
            return $this->successResponse(null, 'Translation deleted successfully.', 204);
        }
        catch (\Exception $e){
            return $this->errorResponse('An error occurred while deleting the translation.', 500);
        }
    }

    public function export(Request $request)
    {
        $query = Translation::orderBy('id', 'DESC');
        if ($request->has('perPage')) {
            $perPage = $request->perPage ?? 10;
            $pageNo = $request->pageNo ?? 1;
            $cloned_query = clone $query;
            $total_records_count = $cloned_query->count();
            $data = ['total_records_count' => $total_records_count, 'per_page' => $perPage, 'page_no' => $pageNo];
            $data['records'] = $query->limit($request->perPage)->offset(($pageNo - 1) * $perPage)->get();
        }
        else{
            // $data = TranslationResource::collection($query->get());
            return $this->errorResponse('data set is too big for this request. please provide "PerPage" Param', 413);
        }

        return $this->successResponse($data, 'Translations retrieved successfully.');



        // // Use chunking to handle large datasets
        // $chunk_size = 1000;
        // $data = [];
        // Translation::chunk($chunk_size, function ($translations) use (&$data) {
        //     foreach ($translations as $translation) {
        //     $data[] = new TranslationResource($translation);

        //         // $data[] = [
        //         //     'id' => $translation->id,
        //         //     'key' => $translation->key,
        //         //     'content' => $translation->content,
        //         //     'locale' => $translation->locale,
        //         //     'tag' => $translation->tag,
        //         // ];
        //     }
        // });

        // return $this->successResponse($data, 'Translations retrieved successfully.');

        // Check if the export is already cached
        // if (Cache::has('translations_export')) {
        //     $data = Cache::get('translations_export');
        //     return $this->successResponse($data, 'Translations retrieved successfully.');
        // }

        // // Dispatch the job to generate the export
        // GenerateTranslationExport::dispatch($request->user());

        // return $this->successResponse([], 'Export process has started. You will be notified when it is ready.');
        
    }
}
