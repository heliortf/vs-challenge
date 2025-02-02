<?php

namespace App\Services;

use Illuminate\Support\Arr;
use App\Repositories\ProductRepository;

/**
 * Product service
 */
class ProductService {

    /**
     * @var ProductRepository
     */
    protected $products;

    /**
     * Constructor
     */
    public function __construct(ProductRepository $products) {
        $this->products = $products;
    }

    /**
     * Deletes a product by id
     *
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        return $this->products->delete($id);
    }

    /**
     * Find a product by id
     *
     * @param int $id
     * @return Product
     */
    public function find($id) {
        return $this->products->find($id);
    }

    /**
     * Return the list of products
     *
     * @return Product[]
     */
    public function list($params) {
        // OBS: We could laravel Validator, but for simplicity where using php

        // If it has filter and matches the search pattern..
        if (Arr::has($params, 'filter') && !empty($params['filter']) && preg_match('/([a-zA-Z]{1,10})\:([a-zA-Z0-9]{1,60})/', $params['filter'])) {
            list($field, $value) = explode(':', $params['filter']);
            $params['filter'] = [
                'field' => $field,
                'value' => $value
            ];
        } else {
            Arr::forget($params, 'filter');
        }

        // If it has sorting and matches the search pattern..
        if (Arr::has($params, 'sort') && !empty($params['sort']) && preg_match('/([a-zA-Z]{1,10})\:(ASC|DESC)?/', $params['sort'])) {
            list($field, $order) = explode(':', $params['sort']);
            $params['sort'] = [
                'field' => $field,
                'order' => $order
            ];
        } else {
            Arr::forget($params, 'sort');
        }

        if (Arr::has($params, 'page') && (!is_numeric($params['page']) || $params['page'] <= 0)) {
            $params['page'] = 1;
        }

        if (Arr::has($params, 'perPage') && (!is_numeric($params['perPage']) || $params['perPage'] <= 0)) {
            $params['perPage'] = 2;
        }

        return $this->products->list($params);
    }

    /**
     * Saves a product
     *
     * @return bool
     */
    public function save($product) {
        return $this->products->save($product);
    }

    /**
     * Updates a product
     *
     * @return bool
     */
    public function update($product) {
        return $this->products->update($product);
    }
}
