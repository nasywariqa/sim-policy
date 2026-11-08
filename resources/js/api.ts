import axios from 'axios';

export const api = axios.create({
  withCredentials: true,
  withXSRFToken: true,
  headers: { Accept: 'application/json' },
});

export function errorText(error: unknown): string {
  if (axios.isAxiosError(error)) {
    const body = error.response?.data as { message?: string; errors?: Record<string, string[]> } | undefined;
    return Object.values(body?.errors ?? {}).flat().join(' ') || body?.message || 'Permintaan gagal. Coba lagi.';
  }
  return error instanceof Error ? error.message : 'Terjadi kesalahan.';
}

export type Membership = {
  id: number;
  user_id: number;
  period_id: number;
  department_id: number | null;
  role_id: number;
  is_chairperson: boolean;
  user?: { id: number; name: string; email?: string };
  role?: { id: number; name: string; slug: string };
  department?: { id: number; name: string } | null;
  overrides?: { permission_id: number; effect: string }[];
};
export type Me = {
  id: number; name: string; email: string; is_super_admin: boolean;
  period: { id: number; name: string } | null;
  membership: { id: number; role: string; role_name: string; department: string | null; is_chairperson: boolean } | null;
  permissions: string[];
};
export type Program = {
  id: number; name: string; purpose: string; description: string | null;
  scheduled_at: string | null; budget: string; target: string | null;
  status: string; department_id: number; period_id: number;
  department?: { id: number; name: string };
  pics: Membership[];
  notes: { id: number; content: string; created_at: string; author?: Membership }[];
  decisions: { id: number; decision: string; note: string | null; created_at: string; author?: Membership }[];
};
export type Directory = {
  periods: { id: number; name: string; is_active: boolean; starts_on: string; ends_on: string }[];
  departments: { id: number; name: string }[];
  users: { id: number; name: string; email: string; is_active: boolean; is_super_admin: boolean }[];
  roles: { id: number; slug: string; name: string }[];
  permissions: { id: number; slug: string; name: string }[];
  memberships: Membership[];
};
export type Criterion = { id: number; code: string; name: string; type: 'benefit' | 'cost'; scale: 'currency' | 'score'; weight: string };
export type SawData = {
  criteria: Criterion[];
  programs: Pick<Program, 'id' | 'name' | 'status' | 'budget'>[];
  assessments: { id: number; program_work_id: number; saw_criterion_id: number; membership_id: number; value: string }[];
  runs: { id: number; created_at: string; results: { id: number; score: string; rank: number; program: { id: number; name: string } }[] }[];
};
