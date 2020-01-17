<?php
declare(strict_types=1);
use CodeShopping\Product;
use CodeShopping\ProductPhoto;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Symfony\Component\Finder\SplFileInfo;

class ProductPhotosTableSeeder extends Seeder
{
    /**
     * @var Collection
     */
    private $allFakerPhotos;
    private $fakerPhotosPath = 'app/faker/product_photos';

    public function run()
    {
        $this->allFakerPhotos = $this->getFakerPhotos();

        $product = Product::all();
        $this->deleteAllPhotosInProductsPath();
        $self = $this;
        $product->each(function ($product) use($self){
            $self->createPhotoDir($product);
            $self->createPhotoModels($product);
        });
    }

    private function getFakerPhotos(): Collection
    {
        $path = storage_path($this->fakerPhotosPath);
        return collect(\File::allFiles($path));
    }
    
    private function deleteAllPhotosInProductsPath()
    {
        $path = ProductPhoto::PRODUCTS_PATH;
        \File::deleteDirectory(storage_path($path), true);
    }

    private function createPhotoDir(Product $product)
    {
        $path = ProductPhoto::photosPath($product->id);
        \File::makeDirectory($path, 0777, true);
    }

    private function createPhotoModels(Product $product)
    {
        foreach(range(1, 5) as $v){
            $this->createPhotoModel($product);
        }
    }

    private function createPhotoModel(Product $product)
    {
        $photo = ProductPhoto::create([
            'product_id' => $product->id,
            'file_name' => 'imagem.jpg'
        ]);
        $this->generatePhoto($photo);
    }

    private function generatePhoto(ProductPhoto $photo)
    {
        $photo->file_name = $this->uploadPhoto($photo->product_id);
        $photo->save();
    }

    private function uploadPhoto($productId): string
    {
        /** @var SplFileInfo $photoFile */
        $photoFile = $this->allFakerPhotos->random();
        $uploadFile = new UploadedFile(
            $photoFile->getRealPath(),
            str_random(16) . '.' . $photoFile->getExtension()
        );
        ProductPhoto::uploadFiles($productId, [$uploadFile]);
        //upload da photo
        return $uploadFile->hashName();
    }

}
