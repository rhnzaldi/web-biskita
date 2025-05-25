<?php

namespace App\Livewire\Company;

use App\Models\Artikel;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Storage;

class ShowArtikels extends Component
{
    use WithFileUploads;

    #[Validate('required|max:50', message: 'Masukkan judul terlebih dahulu !')]
    public $judul;

    #[Validate('required', message: 'Masukkan deskripsi terlebih dahulu !')]
    public $deskripsi;

    #[Validate('image|max:2048', message: 'Masukkan gambar yang valid !')]
    public $image;

    public $selectedArticle;

    public function render()
    {
        $artikels = Artikel::all();
        
        return view('livewire.company.show-artikels', compact('artikels'));
    }

    public function store(){
        $this->validate();
        $artikel = Artikel::create([
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
        ]);
        $this->image->storeAs('article/', $this->image->hashName(), 'public');
        $artikel->update([
            'image' => $this->image->hashName()
        ]);
        $this->reset(['judul', 'deskripsi', 'image']);
        session()->flash('success', 'Artikel baru telah berhasil ditambahkan');
        return redirect()->route('artikel');
    }

    public function update($id){
        $selectedData = Artikel::findOrFail($id);
        $this->validate([
            'judul' => 'required|max:50',
            'deskripsi' => 'required',
        ]);

        if($this->image){
            if ($selectedData->image && Storage::disk('public')->exists('article/'.$selectedData->image)) {
                Storage::disk('public')->delete('article/'.$selectedData->image);
            }
            $newImage = $this->image->hashName();
            $this->image->storeAs('article',$newImage,'public');
        } else{
            $newImage = $selectedData->image;
        }

        $selectedData->update([
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'image' => $newImage
        ]);
        $this->reset();
        session()->flash('success', 'Artikel telah berhasil diperbarui');
        return redirect()->route('artikel');
    }

    public function openModal($id){
        $this->selectedArticle = Artikel::findOrFail($id);
        $this->judul = $this->selectedArticle->judul;
        $this->deskripsi = $this->selectedArticle->deskripsi;
    }
    
    public function resetInput()
    {
        $this->judul = null;
        $this->deskripsi = null;
        $this->image = null;
    }

    public function delete($id){
        $data = Artikel::findOrFail($id);
        $folderPath = 'article/';
        if(Storage::disk('public')->exists($folderPath)){
            Storage::disk('public')->delete($folderPath.$data->image);
        }

        $data->delete();
        session()->flash('success', 'Artikel telah berhasil dihapus');
        return redirect()->route('artikel');
    }
}
