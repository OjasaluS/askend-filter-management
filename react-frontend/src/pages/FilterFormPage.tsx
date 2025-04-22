import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { Filter, Rule, ApiCriteria, ApiComparator, RulePayload } from '../types';
import FilterForm from '../components/FilterForm';
import FilterModal from '../components/FilterModal';
import '../assets/css/style.css';

const FilterFormPage = () => {
    const { id } = useParams<{ id?: string }>();
    const navigate = useNavigate();

    const [isModalView, setIsModalView] = useState(false);

    const [currentName, setCurrentName] = useState('');
    const [currentRules, setCurrentRules] = useState<Partial<Rule>[]>([]);

    const [criteriaOptions, setCriteriaOptions] = useState<ApiCriteria[]>([]);
    const [comparatorOptions, setComparatorOptions] = useState<ApiComparator[]>([]);

    const [isLoading, setIsLoading] = useState(true);
    const [fetchError, setFetchError] = useState<string | null>(null);
    const [isSaving, setIsSaving] = useState(false);
    const [saveError, setSaveError] = useState<string | null>(null);

    const isEditMode = !!id;
    const apiUrl = process.env.REACT_APP_API_URL || 'http://localhost/api';

    useEffect(() => {
        const fetchAllData = async () => {
            setIsLoading(true);
            setFetchError(null);
            try {
                const [criteriaRes, comparatorsRes] = await Promise.all([
                    fetch(`${apiUrl}/criteria`),
                    fetch(`${apiUrl}/comparators`)
                ]);

                if (!criteriaRes.ok) throw new Error(`Failed to fetch criteria: ${criteriaRes.statusText}`);
                if (!comparatorsRes.ok) throw new Error(`Failed to fetch comparators: ${comparatorsRes.statusText}`);

                const fetchedCriteria: ApiCriteria[] = await criteriaRes.json();
                const fetchedComparators: ApiComparator[] = await comparatorsRes.json();

                setCriteriaOptions(fetchedCriteria);
                setComparatorOptions(fetchedComparators);

                if (isEditMode) {
                    const filterRes = await fetch(`${apiUrl}/filters/${id}`);
                    if (!filterRes.ok) throw new Error(`Failed to fetch filter: ${filterRes.statusText}`);
                    const result: Filter = await filterRes.json();
                    console.log(result);
                    setCurrentName(result.name);
                    setCurrentRules(result.rules.map(r => ({
                        criteria: r.criteria,
                        comparator: r.comparator_key,
                        value: r.value
                    })));
                } else {
                    setCurrentName('');
                    setCurrentRules([]);
                }

            } catch (err) {
                if (err instanceof Error) setFetchError(err.message);
                else setFetchError('An unknown error occurred during initial data load');
                console.error("Fetch error:", err);
            } finally {
                setIsLoading(false);
            }
        };

        fetchAllData();
        setIsModalView(false);
    }, [id, isEditMode, apiUrl]);

    const handleNameChange = (name: string) => setCurrentName(name);
    const handleRulesChange = (rules: Partial<Rule>[]) => setCurrentRules(rules);

    const showModalView = () => setIsModalView(true);
    const showInlineView = () => setIsModalView(false);

    const handleSave = async () => {
        setIsSaving(true);
        setSaveError(null);

        const completeRules = currentRules.filter(rule =>
            rule.criteria && rule.comparator && rule.value !== undefined && rule.value !== ''
        );

        if (completeRules.length !== currentRules.length) {
             setSaveError("Please complete all fields for every rule.");
             setIsSaving(false);
             return;
        }

        const rulesToSave: RulePayload[] = completeRules.map(rule => {
            const selectedCriteria = criteriaOptions.find(c => c.name === rule.criteria);
            if (!selectedCriteria) {
                throw new Error(`Invalid criteria selected: ${rule.criteria}`);
            }
            const criteriaId = selectedCriteria.id;

            const selectedComparator = comparatorOptions.find(comp =>
                comp.key === rule.comparator && comp.criteria?.id === criteriaId
            );
            if (!selectedComparator) {
                throw new Error(`Invalid comparator selected: ${rule.comparator} for criteria ${rule.criteria}`);
            }
            const comparatorId = selectedComparator.id;

            return {
                criteria_id: criteriaId,
                comparator_id: comparatorId,
                value: rule.value!
            };
        }).filter(Boolean) as RulePayload[];

        if (rulesToSave.length !== completeRules.length) {
            setSaveError("An error occurred while preparing rules for saving.");
            setIsSaving(false);
            return;
        }

        const filterData = {
            ...(isEditMode ? { id: parseInt(id!, 10) } : {}),
            name: currentName,
            rules: rulesToSave,
        };

        const url = isEditMode ? `${apiUrl}/filters/${id}` : `${apiUrl}/filters`;
        const method = isEditMode ? 'PUT' : 'POST';

        try {
            const response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(filterData),
            });
            if (!response.ok) {
                const errorData = await response.text();
                throw new Error(`Failed to ${isEditMode ? 'update' : 'add'} filter: ${response.statusText} - ${errorData}`);
            }
            navigate('/filters');
        } catch (error) {
            console.error("Save error:", error);
            if (error instanceof Error) setSaveError(error.message);
            else setSaveError('An unknown error occurred while saving.');
            setIsSaving(false);
        }
    };

    const handleCancel = () => {
        if (isModalView) showInlineView();
        else navigate('/filters');
    };

    if (isLoading) return <div className="container">Loading form data...</div>;
    if (fetchError) return <div className="container error">Error loading options: {fetchError}</div>;

    const formProps = {
        name: currentName,
        rules: currentRules,
        onNameChange: handleNameChange,
        onRulesChange: handleRulesChange,
        onSave: handleSave,
        onCancel: handleCancel,
        isSubmitting: isSaving,
        apiError: saveError,
        isEditMode: isEditMode,
        criteriaOptions: criteriaOptions,
        comparatorOptions: comparatorOptions,
    };

    return (
        <div className="container">
            {!isModalView && (
                <div className="form-page-container">
                    <header className="app-header">
                        <h1>{isEditMode ? `Edit Filter: ${currentName || ''}` : 'Add New Filter'}</h1>
                        <button onClick={showModalView} className="action-button pop-out-button" title="Open in modal">
                           ↗ Pop Out
                        </button>
                    </header>
                    <FilterForm {...formProps} />
                </div>
            )}

            {isModalView && (
                <FilterModal
                    isOpen={true}
                    onClose={showInlineView}
                    onSave={handleSave}
                    name={currentName}
                    rules={currentRules}
                    onNameChange={handleNameChange}
                    onRulesChange={handleRulesChange}
                    filterId={isEditMode ? parseInt(id!, 10) : undefined}
                    criteriaOptions={criteriaOptions}
                    comparatorOptions={comparatorOptions}
                />
            )}
        </div>
    );
};

export default FilterFormPage;