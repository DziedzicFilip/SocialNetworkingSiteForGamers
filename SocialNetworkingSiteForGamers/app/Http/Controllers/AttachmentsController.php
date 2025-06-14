<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attachment;

class AttachmentsController extends Controller
{
    public function index()
    {
        $models = Attachment::where('IsActive', true)->get();
        return view('attachments.index', [
            'models' => $models,
        ]);
    }

    public function create()
    {
        $model = new Attachment();
        return view('attachments.create', [
            'model' => $model,
        ]);
    }

    public function addToDB(Request $request)
    {
        $model = new Attachment();
        $model->Title       = $request->input('Title');
        $model->Link        = $request->input('Link');
        $model->ContentHTML = $request->input('ContentHTML');
        $model->Notes       = $request->input('Notes');
        $model->IsActive    = $request->input('IsActive') ? true : false;
        $model->save();
        if ($request->has('internal_events')) {
    $model->internalEvents()->sync($request->input('internal_events'));
}
        return redirect('/attachments');
    }

    public function edit($id)
    {
        $model = Attachment::find($id);
        return view('attachments.edit', [
            'model' => $model,
        ]);
    }

    public function update($id, Request $request)
    {
        $model = Attachment::find($id);
        $model->Title       = $request->input('Title');
        $model->Link        = $request->input('Link');
        $model->ContentHTML = $request->input('ContentHTML');
        $model->Notes       = $request->input('Notes');
        $model->IsActive    = $request->input('IsActive') ? true : false;
        $model->save();

        return redirect('/attachments');
    }

    public function destroy($id)
    {
        Attachment::destroy($id);
        return redirect('/attachments');
    }

    public function show($id)
    {
        $model = Attachment::with('internalEvents')->findOrFail($id);
    
        return view('attachments.show', compact('model'));
    }

       public function showDelete($id)
{
    $model = Attachment::findOrFail($id);
    return view('attachments.delete', ['model' => $model]);
}
 public function delete($id, Request $request)
{
    $model = \App\Models\Attachment::findOrFail($id); 
    $model->IsActive = false;
    $model->save();
    return redirect('/attachments');
}
}
