import './bootstrap';
import React from 'react';
import { createRoot } from 'react-dom/client';
import WelcomeDashboard from './WelcomeDashboard.jsx';

const welcomeRoot = document.getElementById('welcome-dashboard-root');

if (welcomeRoot) {
	createRoot(welcomeRoot).render(React.createElement(WelcomeDashboard));
}
