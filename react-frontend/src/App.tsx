import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import FilterList from './pages/FilterList';
import FilterFormPage from './pages/FilterFormPage';
import './assets/css/style.css';

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/filters" element={<FilterList />} />
        <Route path="/filters/new" element={<FilterFormPage />} />
        <Route path="/filters/:id/edit" element={<FilterFormPage />} />
        <Route path="/" element={<FilterList />} />
      </Routes>
    </Router>
  );
}

export default App;
