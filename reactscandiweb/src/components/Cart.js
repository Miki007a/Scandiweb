import React from 'react';
import '../styles/Cart.scss';
import {useMutation} from "@apollo/client";
import {PLACE_ORDER} from "../graphql/queries";

const Cart = ({ cartItems, setCartItems, clearCart, closeOverlay, isOpen }) => {
    const totalItems = cartItems.reduce((sum, item) => sum + item.quantity, 0);
    const cartTotal = cartItems.reduce(
        (sum, item) => sum + item.price * item.quantity,
        0
    );
    const [placeOrder] = useMutation(PLACE_ORDER);

    const handlePlaceOrder = async () => {
        const orderItems = cartItems.map(item => ({
            productId: item.id,
            quantity: item.quantity,
        }));

        try {
            const { data } = await placeOrder({ variables: { orderItems } });
            console.log('Order placed:', data.placeOrder);
            clearCart();
            closeOverlay();
        } catch (error) {
            console.error("Order placement failed:", error);
        }
    };

    const updateQuantity = (product, quantity) => {
        setCartItems((prevItems) =>
            prevItems
                .map((item) =>
                    item.id === product.id && JSON.stringify(item.options) === JSON.stringify(product.options)
                        ? { ...item, quantity }
                        : item
                )
                .filter((item) => item.quantity > 0)
        );
    };

    const toKebabCase = (str) => str.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();

    return (
        <>
            {isOpen && (
                <div 
                    className="cart-overlay"
                    data-testid="cart-overlay"
                >
                    <h2>My Bag, {totalItems} {totalItems === 1 ? 'Item' : 'Items'}</h2>
                    <ul className="cart-items">
                        {cartItems && cartItems.map((item) => (
                            <li key={`${item.id}-${item.selectedOptions}`} className="cart-item">
                                <div className="cart-item-grid">
                                    <div className="cart-item-info">
                                        <h3>{item.name}</h3>
                                        <p className="price">${item.price.toFixed(2)}</p>
                                        <div className="attribute-options">
                                            {item.options.map((option) => (
                                                <div 
                                                    key={option.name} 
                                                    className="attribute-set"
                                                    data-testid={`cart-item-attribute-${toKebabCase(option.name)}`}
                                                >
                                                    <span className="attribute-name">{option.name}:</span>
                                                    <div className="attribute-items">
                                                        {option.allOptions.map((opt) => {
                                                            const isSelected = option.value === opt.displayValue;
                                                            const baseTestId = `cart-item-attribute-${toKebabCase(option.name)}-${toKebabCase(opt.displayValue)}`;
                                                            
                                                            return (
                                                                <button
                                                                    key={opt.value}
                                                                    className={`
                                                                        ${opt.type === 'swatch' ? 'color-attribute' : 'size-attribute'}
                                                                        ${isSelected ? 'selected' : ''}
                                                                    `}
                                                                    style={opt.type === 'swatch' ? { backgroundColor: opt.value } : {}}
                                                                    data-testid={isSelected ? `${baseTestId}-selected` : baseTestId}
                                                                >
                                                                    {opt.type === 'swatch' ? '' : opt.displayValue}
                                                                </button>
                                                            );
                                                        })}
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                    
                                    <div className="quantity-image-container">
                                        <div className="quantity-controls">
                                            <button
                                                onClick={() => updateQuantity(item, item.quantity + 1)}
                                                className="quantity-button"
                                                data-testid="cart-item-amount-increase"
                                            >
                                                +
                                            </button>
                                            <span data-testid="cart-item-amount">{item.quantity}</span>
                                            <button
                                                onClick={() => updateQuantity(item, item.quantity - 1)}
                                                className="quantity-button"
                                                data-testid="cart-item-amount-decrease"
                                            >
                                                -
                                            </button>
                                        </div>
                                        <div className="cart-item-image">
                                            <img src={item.image} alt={item.name} />
                                        </div>
                                    </div>
                                </div>
                            </li>
                        ))}
                    </ul>
                    <div className="cart-footer">
                    <div className="cart-total">
                        <h3 data-testid="cart-total">Total: ${cartTotal.toFixed(2)}</h3>
                    </div>
                    <button
                        className="place-order"
                        onClick={handlePlaceOrder}
                        disabled={cartItems.length === 0}
                    >
                        PLACE ORDER
                    </button>
                    </div>
                </div>
            )}
        </>
    );
};

export default Cart;
