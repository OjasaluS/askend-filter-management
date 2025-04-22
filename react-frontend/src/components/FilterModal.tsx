import React, { useState } from 'react';
import { Filter, Rule, ApiCriteria, ApiComparator } from '../types';
import FilterForm from './FilterForm';
import '../assets/css/style.css';

interface FilterModalProps {
    isOpen: boolean;
    onClose: () => void;
    onSave: (filterData: Omit<Filter, 'id'> | Filter) => Promise<void>;

    name: string;
    rules: Partial<Rule>[];
    onNameChange: (name: string) => void;
    onRulesChange: (rules: Partial<Rule>[]) => void;

    filterId?: number;

    criteriaOptions: ApiCriteria[];
    comparatorOptions: ApiComparator[];
}

const FilterModal: React.FC<FilterModalProps> = ({
    isOpen,
    onClose,
    onSave,
    name,
    rules,
    onNameChange,
    onRulesChange,
    filterId,
    criteriaOptions,
    comparatorOptions
}) => {
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [apiError, setApiError] = useState<string | null>(null);

    const isEditMode = filterId !== undefined;

    const handleSaveWrapper = async () => {
        setIsSubmitting(true);
        setApiError(null);

        const completeRules = rules.filter(rule => rule.criteria && rule.comparator && rule.value !== undefined && rule.value !== '');
        if (completeRules.length !== rules.length) {
             setApiError("Please complete all fields for every rule.");
             setIsSubmitting(false);
             return;
        }
        const rulesToSave = completeRules.map(rule => rule);

        const filterData = {
            ...(isEditMode ? { id: filterId } : {}),
            name: name,
            rules: rulesToSave as Rule[],
        };

        try {
            await onSave(filterData);
        } catch (error) {
             if (error instanceof Error) setApiError(error.message);
             else setApiError('An unknown error occurred while saving.');
             setIsSubmitting(false);
        }
    };

    if (!isOpen) return null;

    return (
        <div className="modal-overlay">
            <div className="modal-container">
                <header className="form-header">
                    <h1>{isEditMode ? 'Edit Filter' : 'Add New Filter'}</h1>
                    <button onClick={onClose} className="close-button" aria-label="Close" disabled={isSubmitting}>×</button>
                </header>
                <FilterForm
                    name={name}
                    rules={rules}
                    onNameChange={onNameChange}
                    onRulesChange={onRulesChange}
                    onSave={handleSaveWrapper}
                    onCancel={onClose}
                    isSubmitting={isSubmitting}
                    apiError={apiError}
                    isEditMode={isEditMode}
                    criteriaOptions={criteriaOptions}
                    comparatorOptions={comparatorOptions}
                />
            </div>
        </div>
    );
};

export default FilterModal;
