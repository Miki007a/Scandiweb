import React, {useEffect} from 'react';
import { useQuery } from '@apollo/client';
import { useParams } from 'react-router-dom';
import {GET_PRODUCTS, GET_PRODUCTS_BY_CATEGORY} from '../graphql/queries';
import ProductCard from './ProductCard';
import '../styles/ProductList.scss';

const ProductList = ({isCartOverlayOpen, addToCart }) => {
    const { category } = useParams();

    const queryToUse = category === 'all' ? GET_PRODUCTS : GET_PRODUCTS_BY_CATEGORY;

    const { loading, error, data } = useQuery(queryToUse, {
        variables: category !== 'all' ? { categoryName: category } : {},
    });

    useEffect(() => {
        if (data) {
            console.log(data);
        }
    }, [data]);
    return (
        <section className={`product-list ${isCartOverlayOpen ? 'dimmed' : ''}`}>
            <h2>{category && category.toUpperCase()}</h2>
            <div className="product-grid">
                {!loading && !error && data &&
                    (category === 'all'
                            ? data.getProducts.map((product) => (
                                <ProductCard 
                                    key={product.id} 
                                    product={product}
                                    addToCart={addToCart}
                                />
                            ))
                            : data.getProductsByCategory.map((product) => (
                                <ProductCard 
                                    key={product.id} 
                                    product={product}
                                    addToCart={addToCart}
                                />
                            ))
                    )
                }
            </div>
        </section>
    );
};

export default ProductList;
