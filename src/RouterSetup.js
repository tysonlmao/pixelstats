import { BrowserRouter, Routes, Route } from 'react-router-dom';
import App from './App'; // Import your App component here

function RouterSetup() {
    return (
        <BrowserRouter>
            <Routes>
                <Route path="/" element={<App />} />
            </Routes>
        </BrowserRouter>
    );
}

export default RouterSetup;
