import React from 'react';
import { Rule, ApiCriteria, ApiComparator } from '../types';
import '../assets/css/style.css';

interface FilterFormProps {
    name: string;
    rules: Partial<Rule>[];
    onNameChange: (name: string) => void;
    onRulesChange: (rules: Partial<Rule>[]) => void;
    onSave: () => Promise<void>;
    onCancel: () => void;
    isSubmitting: boolean;
    apiError: string | null;
    isEditMode: boolean;
    criteriaOptions: ApiCriteria[];
    comparatorOptions: ApiComparator[];
}

const FilterForm: React.FC<FilterFormProps> = ({
    name,
    rules,
    onNameChange,
    onRulesChange,
    onSave,
    onCancel,
    isSubmitting,
    apiError,
    isEditMode,
    criteriaOptions,
    comparatorOptions
}) => {

    const addRuleRow = () => {
        const defaultCriteriaName = criteriaOptions.length > 0 ? criteriaOptions[0].name : '';
        onRulesChange([...rules, { criteria: defaultCriteriaName, comparator: '', value: '' }]);
    };

    const removeRuleRow = (index: number) => {
        onRulesChange(rules.filter((_, i) => i !== index));
    };

    const handleRuleChange = (index: number, field: keyof Rule, value: string) => {
        const newRules = rules.map((rule, i) => {
            if (i === index) {
                const updatedRule = { ...rule, [field]: value };
                if (field === 'criteria') {
                    updatedRule.comparator = '';
                }
                return updatedRule;
            }
            return rule;
        });
        onRulesChange(newRules);
    };

    const handleSubmit = async (event: React.FormEvent) => {
        event.preventDefault();
        onSave();
    };

    const getComparatorsForRule = (criteriaName: string | undefined): ApiComparator[] => {
        if (!criteriaName) return [];
        const selectedCriteria = criteriaOptions.find(c => c.name === criteriaName);
        if (!selectedCriteria) return [];

        return comparatorOptions.filter(comp => comp.criteria?.id === selectedCriteria.id);
    };

    return (
        <div className="filter-form-wrapper">
            <form onSubmit={handleSubmit}>
                <div className="form-scrollable-content">
                    {apiError && <div className="error api-error">Error: {apiError}</div>}

                    <div className="form-group">
                        <label htmlFor="filterName">Filter name</label>
                        <input
                            type="text"
                            id="filterName"
                            value={name}
                            onChange={(e) => onNameChange(e.target.value)}
                            required
                            disabled={isSubmitting}
                        />
                    </div>

                    <fieldset className="criteria-fieldset">
                        <legend>Criteria</legend>
                        {rules.map((rule, index) => {
                            const availableComparators = getComparatorsForRule(rule.criteria);

                            return (
                                <div key={index} className="criteria-row">
                                    <select
                                        value={rule.criteria || ''}
                                        onChange={(e) => handleRuleChange(index, 'criteria', e.target.value)}
                                        disabled={isSubmitting}
                                    >
                                        <option value="" disabled>Select Criteria</option>
                                        {criteriaOptions.map(opt => (
                                            <option key={opt.id} value={opt.name}>{opt.name}</option>
                                        ))}
                                    </select>

                                    <select
                                        value={rule.comparator || ''}
                                        onChange={(e) => handleRuleChange(index, 'comparator', e.target.value)}
                                        disabled={isSubmitting || !rule.criteria || availableComparators.length === 0}
                                    >
                                        <option value="" disabled>Select Comparator</option>
                                        {availableComparators.map(opt => (
                                            <option key={opt.id} value={opt.key}>{opt.label}</option>
                                        ))}
                                    </select>

                                    <input
                                        type={criteriaOptions.find(c => c.name === rule.criteria)?.type === 'number' ? 'number' : rule.criteria === 'Date' ? 'date' : 'text'}
                                        value={rule.value || ''}
                                        onChange={(e) => handleRuleChange(index, 'value', e.target.value)}
                                        placeholder="Value"
                                        required
                                        disabled={isSubmitting}
                                    />
                                    <button
                                        type="button"
                                        onClick={() => removeRuleRow(index)}
                                        className="remove-row-button"
                                        aria-label="Remove rule"
                                        disabled={isSubmitting}
                                    >
                                        -
                                    </button>
                                </div>
                            );
                        })}
                        <button
                            type="button"
                            onClick={addRuleRow}
                            className="add-row-button"
                            disabled={isSubmitting || criteriaOptions.length === 0}
                        >
                            + Add criteria
                        </button>
                    </fieldset>
                </div>

                <div className="form-actions">
                    <button type="button" onClick={onCancel} className="action-button cancel-button" disabled={isSubmitting}>
                        Cancel
                    </button>
                    <button type="submit" className="action-button save-button" disabled={isSubmitting}>
                        {isSubmitting ? 'Saving...' : (isEditMode ? 'Save Changes' : 'Save Filter')}
                    </button>
                </div>
            </form>
        </div>
    );
};

export default FilterForm;
