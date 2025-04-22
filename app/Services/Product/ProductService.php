<?php

namespace App\Services\Product;

use App\Repositories\Product\ProductInterface;
use App\Traits\FileUploadTrait;

class ProductService
{
    use FileUploadTrait;


    protected ProductInterface $productRepository;

    public function __construct(ProductInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getProductById($id)
    {
        return $this->productRepository->find($id);
    }

    public function createProduct(array $data)
    {
        if (isset($data['image'])) {
            $data['image'] = $this->uploadFile($data['image']);
        }

        return $this->productRepository->create($data);
    }

    public function updateProduct($id, array $data)
    {
        $product = $this->productRepository->find($id);

        // Nếu có ảnh mới và sản phẩm đã có ảnh cũ -> Xoá ảnh cũ
        if (isset($data['image']) && $product->image) {
            $this->deleteFile($product->image);
        }

        // Nếu có ảnh mới, upload ảnh và cập nhật đường dẫn
        if (isset($data['image'])) {
            $data['image'] = $this->uploadFile($data['image']);
        }
        return $this->productRepository->update($id, $data);
    }

    public function deleteProduct($id)
    {
        $product = $this->productRepository->find($id);
        $this->deleteFile($product->image);
        return $this->productRepository->delete($id);
    }
}
