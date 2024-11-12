import React, { useState, useEffect } from 'react';
import ApolloProvider from './ApolloProvider';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Header from './components/Header';
import ProductList from './components/ProductList';
import ProductDetails from './components/ProductDetails';
import Cart from './components/Cart';
import './styles/App.scss';


const App = () => {
    const [isCartOverlayOpen, setCartOverlayOpen] = useState(false);
    const [selectedCategory, setSelectedCategory] = useState('');
    const [cartItems, setCartItems] = useState([]);

    useEffect(() => {
        if (isCartOverlayOpen) {
            document.body.classList.add('cart-open');
        } else {
            document.body.classList.remove('cart-open');
        }
        
        return () => {
            document.body.classList.remove('cart-open');
        };
    }, [isCartOverlayOpen]);

    const toggleCartOverlay = () => {
        setCartOverlayOpen((prev) => !prev);
    };

    const handleCategoryChange = (category) => {
        setSelectedCategory(category);
    };

    const addToCart = (item) => {
        setCartItems((prevItems) => {
            const existingItem = prevItems.find(
                (cartItem) =>
                    cartItem.id === item.id &&
                    JSON.stringify(cartItem.options) === JSON.stringify(item.options)
            );
            if (existingItem) {
                return prevItems.map((cartItem) =>
                    cartItem.id === existingItem.id && cartItem.options === existingItem.options
                        ? { ...cartItem, quantity: cartItem.quantity + 1 }
                        : cartItem
                );
            }
            return [...prevItems, { ...item, quantity: 1 }];
        });
        
       
        const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
        const maxScroll = document.documentElement.scrollHeight - window.innerHeight;
        const scrollRatio = currentScroll / maxScroll;
        const baseDelay = 600; 
        const scrollDelay = Math.round(scrollRatio * baseDelay);

    
        if (currentScroll > 0) {
            window.scrollTo({ top: 0, behavior: 'smooth' });
            setTimeout(() => {
                toggleCartOverlay();
            }, scrollDelay);
        } else {
        
            toggleCartOverlay();
        }
    };

    const clearCart = () => {
        setCartItems([]);
        setCartOverlayOpen(false);
    };

    return (
        <ApolloProvider>
            <Router basename="/">
                <div className={`App`}>
                    <Header
                        setSelectedCategory={handleCategoryChange}
                        toggleCartOverlay={toggleCartOverlay}
                        cartItemsCount={cartItems.length}
                    />
                    <Cart
                        cartItems={cartItems}
                        setCartItems={setCartItems}
                        clearCart={clearCart}
                        closeOverlay={toggleCartOverlay}
                        isOpen={isCartOverlayOpen}
                    />
                    <main className={`main-content ${isCartOverlayOpen ? 'dimmed' : ''}`}>
                        <Routes>
                            <Route 
                                path="/:category" 
                                element={<ProductList 
                                    isCartOverlayOpen={isCartOverlayOpen} 
                                    addToCart={addToCart}
                                />} 
                            />
                            <Route 
                                path="/product/:id" 
                                element={<ProductDetails 
                                    addToCart={addToCart} 
                                    isCartOverlayOpen={isCartOverlayOpen}
                                />} 
                            />
                        </Routes>
                    </main>
                </div>
            </Router>
        </ApolloProvider>
    );
};

export default App;
