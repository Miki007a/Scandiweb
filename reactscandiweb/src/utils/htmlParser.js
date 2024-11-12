import React from 'react';

class HtmlParser {
    static parseHtml(htmlString) {
        const div = document.createElement('div');
        div.innerHTML = htmlString;
        return Array.from(div.childNodes).map((node, index) => {
            return this.convertNodeToReactElement(node, index);
        });
    }

    static convertNodeToReactElement(node, index) {
        if (node.nodeType === 3) { 
            return node.textContent;
        }

        if (node.nodeType === 1) { 
            const children = Array.from(node.childNodes).map((child, childIndex) => 
                this.convertNodeToReactElement(child, childIndex)
            );

            return React.createElement(
                node.tagName.toLowerCase(),
                { key: index },
                children
            );
        }

        return null;
    }
}

export default HtmlParser; 