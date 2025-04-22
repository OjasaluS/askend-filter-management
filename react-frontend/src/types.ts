export interface Rule {
  criteria: string;
  comparator: string;
  comparator_key: string;
  value: string;
}

export interface Filter {
  id: number;
  name: string;
  rules: Rule[];
}

export interface ApiCriteria {
  id: number;
  name: string;
  type?: string;
}

export interface ApiComparator {
  id: number;
  key: string;
  label: string;
  criteria: {
    id: number;
    name: string;
    type?: string;
  };
}

export interface RulePayload {
  criteria_id: number;
  comparator_id: number;
  value: string;
}
