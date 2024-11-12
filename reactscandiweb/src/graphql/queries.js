import { gql } from '@apollo/client';

export const GET_PRODUCTS = gql`
    query GetProducts {
        getProducts {
        id
        name
        inStock
        prices {
            amount
            currency {
                label
                symbol
            }
        }
        gallery {
            imageUrl
        }
    }
    }
`;
export const GET_CATEGORIES = gql`
    query GetCategories {
        getCategories {
            id
            name
        }
    }
`;
export const GET_PRODUCTS_BY_CATEGORY = gql`
    query GetProductsByCategory($categoryName: String!) {
        getProductsByCategory(categoryName: $categoryName) {
            id
            name
            inStock
            prices {
                amount
                currency {
                    label
                    symbol
                }
            }
            gallery {
                imageUrl
            }
        }
    }
`;

export const GET_PRODUCT_BY_ID = gql`
    query GetProductById($id: String!) {
        getProductById(id: $id) {
            id
            name
            inStock
            description
            brand
            prices {
                amount
                currency {
                    label
                    symbol
                }
            }
            gallery {
                imageUrl
            }
            attributes {
                name
                type
                items {
                    displayValue
                    value
                }
            }
            category {
                name
            }
        }
    }
`;
export const PLACE_ORDER = gql`
    mutation PlaceOrder($orderItems: [OrderItemInput!]!) {
        placeOrder(orderItems: $orderItems) {
            id
            total
            orderItems {
                id
                product {
                    id
                    name
                }
                quantity
            }
        }
    }
`;