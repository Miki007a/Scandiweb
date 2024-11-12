import React, {useEffect, useState} from 'react';
import { useQuery } from '@apollo/client';
import { GET_CATEGORIES } from '../graphql/queries';
import Cart from './Cart';
import '../styles/Header.scss';
import {Link, useNavigate} from "react-router-dom";

const Header = ({ setSelectedCategory, cartItemsCount, toggleCartOverlay,isCartOverlayOpen }) => {
    const { loading, error, data } = useQuery(GET_CATEGORIES);
    const [activeCategory, setActiveCategory] = useState('');
    const navigate = useNavigate();

    useEffect(() => {
        if (data && data.getCategories.length > 0 && !activeCategory) {
            const firstCategory = data.getCategories[0];
            setActiveCategory(firstCategory);
            setSelectedCategory(firstCategory);
            navigate(`/${firstCategory.name}`);
        }
    }, [data, setSelectedCategory, activeCategory]);
    
    const handleCategoryClick = (category) => {
        setActiveCategory(category);
        setSelectedCategory(category);
    };

    return (
        <header className="header">
            <nav className="navigation">
                <ul className="nav-left">
                    {!loading && !error && data.getCategories.map((category) => (
                        <li key={category.id}>
                            <Link
                                className={activeCategory && activeCategory.id === category.id ? 'active' : ''}
                                onClick={() => handleCategoryClick(category)}
                                data-testid={activeCategory && activeCategory.name.toLowerCase() === category.name.toLowerCase() ? 'active-category-link' : 'category-link'}
                                to={`/${category.name}`}>
                                {category.name.toUpperCase()}
                            </Link>
                        </li>
                    ))}
                </ul>

                <div className="logo">
                    <img src='/icons/shopping-bag.png' alt="Shopping Bag"/>
                </div>

                <ul className="nav-right">
                    <li className="cart-container">
                        <button
                            data-testid="cart-btn"
                            className="cart-button"
                            onClick={toggleCartOverlay}
                        >
                            🛒
                            {cartItemsCount > 0 && (
                                <span className="item-count-bubble">{cartItemsCount==1 ? '1' : 'X'}</span>
                            )}
                        </button>
                        {isCartOverlayOpen && <Cart />}
                    </li>
                </ul>
            </nav>
        </header>
    );
};

export default Header;
