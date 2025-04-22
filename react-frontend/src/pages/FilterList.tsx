import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { Filter } from '../types';
import '../assets/css/style.css';

const FilterList = () => {
    const [data, setData] = useState<Filter[] | null>(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<Error | null>(null);
    const [apiError, setApiError] = useState<string | null>(null);
    const navigate = useNavigate();

    const apiUrl = process.env.REACT_APP_API_URL || 'http://localhost/api';

    useEffect(() => {
        const fetchData = async () => {
            setLoading(true);
            setApiError(null);
            try {
                const response = await fetch(`${apiUrl}/filters`);
                if (!response.ok) {
                    throw new Error(`Network response was not ok: ${response.statusText}`);
                }
                const result: Filter[] = await response.json();
                setData(result);
                setError(null);
            } catch (fetchError) {
                if (fetchError instanceof Error) {
                    setError(fetchError);
                } else {
                    setError(new Error('An unknown fetch error occurred'));
                }
                console.error("Fetch error:", fetchError);
            } finally {
                setLoading(false);
            }
        };
        fetchData();
    }, [apiUrl]);

    const handleAddFilter = () => {
        navigate('/filters/new');
    };

    const handleEdit = (id: number) => {
        navigate(`/filters/${id}/edit`);
    };

    const handleDelete = async (id: number, name: string) => {
        if (window.confirm(`Are you sure you want to delete the filter "${name}"?`)) {
            setApiError(null);
            try {
                const response = await fetch(`${apiUrl}/filters/${id}`, { method: 'DELETE' });
                if (!response.ok) {
                    const errorData = await response.text();
                    throw new Error(`Failed to delete filter: ${response.statusText} - ${errorData}`);
                }
                setData(currentData => currentData ? currentData.filter(filter => filter.id !== id) : null);
            } catch (deleteError) {
                console.error("Delete error:", deleteError);
                if (deleteError instanceof Error) {
                    setApiError(deleteError.message);
                } else {
                    setApiError('An unknown error occurred during deletion.');
                }
            }
        }
    };

    if (loading && !data) return <div className="container">Loading filters...</div>;
    if (error) return <div className="container error">Error loading filters: {error.message}</div>;

    return (
        <div className="container">
            <header className="app-header">
                <h1>Filter Management</h1>
                <button onClick={handleAddFilter} className="add-button">
                    Add Filter
                </button>
            </header>

            {apiError && <div className="container error api-error">Error: {apiError}</div>}

            {data && data.length > 0 ? (
                <table className="filter-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Rules</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {data.map((filter) => (
                            <tr key={filter.id}>
                                <td>{filter.name}</td>
                                <td>
                                    {filter.rules.length > 0 ? (
                                        <ul className="rule-list-grid">
                                            <li className="rule-item-header">
                                                <span className="rule-header-part">Criteria</span>
                                                <span className="rule-header-part">Comparator</span>
                                                <span className="rule-header-part">Value</span>
                                            </li>
                                            {filter.rules.map((rule, index) => (
                                                <li key={index} className="rule-item-row">
                                                    <span className="rule-part">{rule.criteria}</span>
                                                    <span className="rule-part">{rule.comparator}</span>
                                                    <span className="rule-part">{rule.value}</span>
                                                </li>
                                            ))}
                                        </ul>
                                    ) : (
                                        <span>No rules</span>
                                    )}
                                </td>
                                <td className="actions-cell">
                                    <button onClick={() => handleEdit(filter.id)} className="action-button edit-button">
                                        Edit
                                    </button>
                                    <button onClick={() => handleDelete(filter.id, filter.name)} className="action-button delete-button">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            ) : (
                !loading && <p>No filters found.</p>
            )}
        </div>
    );
};

export default FilterList;