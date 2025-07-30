import React from 'react';
import { createRoot } from 'react-dom/client';
import App from './components/App';
import './css/app.css';

// Tạo root element
const container = document.getElementById('app');
const root = createRoot(container);

// Render ứng dụng React
root.render(
    <React.StrictMode>
        <App />
    </React.StrictMode>
);
