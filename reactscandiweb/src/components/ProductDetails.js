import React, { useEffect, useState } from 'react';
import { useQuery } from '@apollo/client';
import { useParams } from 'react-router-dom';
import { GET_PRODUCT_BY_ID } from '../graphql/queries';
import '../styles/ProductDetails.scss';
import HtmlParser from '../utils/htmlParser';

const ProductDetails = ({ addToCart, isCartOverlayOpen }) => {
    const { id } = useParams();
    const { loading, error, data } = useQuery(GET_PRODUCT_BY_ID, {
        variables: { id },
    });
    const [product, setProduct] = useState(null);
    const [selectedImage, setSelectedImage] = useState(null);
    const [selectedAttributes, setSelectedAttributes] = useState({});
    const [currentImageIndex, setCurrentImageIndex] = useState(0);
    const [allAttributesSelected, setAllAttributesSelected] = useState(false);

    useEffect(() => {
        if (data && data.getProductById) {
            console.log(data.getProductById);
            setProduct(data.getProductById);
            setSelectedImage(data.getProductById.gallery[0].imageUrl);

            const initialAttributes = {};
            data.getProductById.attributes.forEach(attribute => {
                initialAttributes[attribute.name] = {
                    selected: null,
                    options: attribute.items.map(item => ({
                        displayValue: item.displayValue,
                        value: item.value,
                        type: attribute.type
                    }))
                };
            });
            setSelectedAttributes(initialAttributes);
        }
    }, [data]);

    useEffect(() => {
        if (product && product.attributes) {
            const allSelected = product.attributes.every(attr => 
                selectedAttributes[attr.name]?.selected !== null
            );
            setAllAttributesSelected(allSelected);
        }
    }, [selectedAttributes, product]);

    const handleAttributeSelect = (attributeName, itemValue) => {
        setSelectedAttributes(prevState => ({
            ...prevState,
            [attributeName]: {
                ...prevState[attributeName],
                selected: itemValue
            }
        }));
    };

    const handleAddToCart = () => {
        const cartItem = {
            id: product.id,
            name: product.name,
            price: product.prices[0].amount,
            image: selectedImage,
            quantity: 1,
            options: Object.entries(selectedAttributes).map(([name, attr]) => ({
                name,
                value: attr.selected,
                allOptions: attr.options
            })),
        };
        addToCart(cartItem);
    };

    const handlePrevImage = () => {
        setCurrentImageIndex(prevIndex => {
            const newIndex = prevIndex === 0 ? product.gallery.length - 1 : prevIndex - 1;
            setSelectedImage(product.gallery[newIndex].imageUrl);
            return newIndex;
        });
    };

    const handleNextImage = () => {
        setCurrentImageIndex(prevIndex => {
            const newIndex = prevIndex === product.gallery.length - 1 ? 0 : prevIndex + 1;
            setSelectedImage(product.gallery[newIndex].imageUrl);
            return newIndex;
        });
    };

    const toKebabCase = (string) => {
        return string
            .toLowerCase()
            .replace(/[^a-zA-Z0-9]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-+|-+$/g, '');
    };

    return (
        <section className={`product-details ${isCartOverlayOpen ? 'dimmed' : ''}`}>
            {!loading && !error && product && (
                <>
                    <div 
                        className="product-image-gallery"
                        data-testid="product-gallery"
                    >
                        {product.gallery.map((image, index) => (
                            <img
                                key={index}
                                src={image.imageUrl}
                                alt={product.name}
                                className={currentImageIndex === index ? 'selected' : ''}
                                onClick={() => {
                                    setCurrentImageIndex(index);
                                    setSelectedImage(image.imageUrl);
                                }}
                            />
                        ))}
                    </div>

                    <div className="product-main-image">
                        <img src={selectedImage} alt={product.name} />
                        {product.gallery.length > 1 && (
                            <div className="image-controls">
                                <button 
                                    onClick={handlePrevImage}
                                    aria-label="Previous image"
                                >
                                    &lt;
                                </button>
                                <button 
                                    onClick={handleNextImage}
                                    aria-label="Next image"
                                >
                                    &gt;
                                </button>
                            </div>
                        )}
                    </div>

                    <div className="product-info">
                        <h2>{product.name}</h2>

                        <div className="attributes">
                            {product.attributes && product.attributes.length > 0 && product.attributes.map((attribute) => (
                                <div 
                                    key={attribute.name} 
                                    className="attribute-section"
                                    data-testid={`product-attribute-${toKebabCase(attribute.name)}`}
                                >
                                    <h4>{attribute.name}:</h4>
                                    <ul className={attribute.type === 'swatch' ? 'color-options' : 'size-options'}>
                                        {attribute.items.map((item) => (
                                            <li
                                                key={item.value}
                                                data-testid={`product-attribute-${attribute.name.toLowerCase()}-${item.value}`}
                                                className={`attribute-item ${
                                                    attribute.type === 'swatch' ? 'color-item' : 'size-item'
                                                } ${
                                                    selectedAttributes[attribute.name]?.selected === item.displayValue ? 'selected' : ''
                                                }`}
                                                style={attribute.type === 'swatch' ? { backgroundColor: item.value } : {}}
                                                onClick={() => handleAttributeSelect(attribute.name, item.displayValue)}
                                            >
                                                {attribute.type === 'swatch' ? '' : item.displayValue}
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                            ))}
                        </div>

                        <div className="price">
                            Price: ${product.prices[0].amount.toFixed(2)}
                        </div>

                        <button 
                            className="add-to-cart" 
                            onClick={handleAddToCart}
                            disabled={!allAttributesSelected}
                            data-testid="add-to-cart"
                        >
                            ADD TO CART
                        </button>

                        <div 
                            className="description"
                            data-testid="product-description"
                        >
                            {HtmlParser.parseHtml(product.description)}
                        </div>
                    </div>
                </>
            )}
        </section>
    );
};

export default ProductDetails;
