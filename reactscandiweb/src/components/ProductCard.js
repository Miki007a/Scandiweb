import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import '../styles/ProductCard.scss';
import { useLazyQuery } from '@apollo/client';
import { GET_PRODUCT_BY_ID } from '../graphql/queries';

const ProductCard = ({ product, addToCart }) => {
    const [isHovered, setIsHovered] = useState(false);
    const { id, name, inStock, prices, gallery } = product;
    const mainImage = gallery[0].imageUrl;
    const price = prices[0].amount.toFixed(2); 
    const isOutOfStock = !inStock;

    const [getProduct, { loading, data }] = useLazyQuery(GET_PRODUCT_BY_ID, {
        onCompleted: (data) => {
            if (data && data.getProductById) {
                const initialAttributes = {};
                
                data.getProductById.attributes.forEach(attribute => {
                    initialAttributes[attribute.name] = {
                        selected: attribute.items[0].displayValue,
                        options: attribute.items.map(item => ({
                            displayValue: item.displayValue,
                            value: item.value,
                            type: attribute.type
                        }))
                    };
                });
                const cartItem = {
                    id: product.id,
                    name: product.name,
                    price: product.prices[0].amount,
                    image: mainImage,
                    quantity: 1,
                    options: Object.entries(initialAttributes).map(([name, attr]) => ({
                        name,
                        value: attr.selected,
                        allOptions: attr.options
                    })),
                };

                addToCart(cartItem);
            }
        }
    });

    const toKebabCase = (string) => {
        return string
            .toLowerCase()
            .replace(/[^a-zA-Z0-9]/g, '-') 
            .replace(/-+/g, '-')           
            .replace(/^-+|-+$/g, '');      
    };

    const handleQuickShop = (e) => {
        e.preventDefault();
        getProduct({ variables: { id } });
    };

    return (
        <div
            className={`product-card ${isOutOfStock ? 'out-of-stock' : ''}`}
            data-testid={`product-${toKebabCase(name)}`}
            onMouseEnter={() => setIsHovered(true)}
            onMouseLeave={() => setIsHovered(false)}
        >
            {isOutOfStock ? (
                <div className="product-link disabled">
                    <div className="product-image">
                        <img src={mainImage} alt={name} />
                        <span className="stock-label">OUT OF STOCK</span>
                    </div>

                    <h3>{name}</h3>
                    <p>${price}</p>
                </div>
            ) : (
                <Link to={`/product/${id}`} className="product-link">
                    <div className="product-image">
                        <img src={mainImage} alt={name} />
                    </div>

                    {isHovered && product.inStock && (
                        <button 
                            className="quick-shop"
                            onClick={handleQuickShop}
                            disabled={loading}
                            aria-label="Quick add to cart"
                        >
                            <span className="cart-icon">🛒</span>
                        </button>
                    )}
                    <h3>{name}</h3>
                    <p>${price}</p>

               
                </Link>
            )}

        </div>


    );
};

export default ProductCard;
