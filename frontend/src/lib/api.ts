import type { CouncilSessionView } from './types'

const API_BASE = process.env.BACKEND_URL ?? 'http://localhost:8000'

async function apiFetch<T>(path: string, options?: RequestInit): Promise<T> {
  const res = await fetch(`${API_BASE}${path}`, {
    ...options,
    headers: { 'Content-Type': 'application/json', ...options?.headers },
  })

  const data = await res.json()

  if (!res.ok) {
    throw new Error((data as { error?: string }).error ?? `Erreur HTTP ${res.status}`)
  }

  return data as T
}

export async function getCouncilSession(id: string): Promise<CouncilSessionView> {
  return apiFetch(`/api/council-sessions/${id}`)
}

export async function createCouncilSession(body: {
  townHallCode: string
  date: string
  orderOfBusiness: string
}): Promise<{ id: string }> {
  return apiFetch('/api/council-sessions', {
    method: 'POST',
    body: JSON.stringify(body),
  })
}

export async function sendCouncilSessionInvitations(sessionId: string): Promise<void> {
  await apiFetch(`/api/council-sessions/${sessionId}/invitations`, { method: 'POST' })
}

export async function registerAttendance(
  sessionId: string,
  body: { councilorId: string; status: string; proxyHolderId?: string },
): Promise<void> {
  await apiFetch(`/api/council-sessions/${sessionId}/attendances`, {
    method: 'POST',
    body: JSON.stringify(body),
  })
}

export async function openCouncilSession(sessionId: string): Promise<void> {
  await apiFetch(`/api/council-sessions/${sessionId}/open`, { method: 'POST' })
}

export async function addDeliberation(
  sessionId: string,
  body: { title: string; description: string },
): Promise<{ id: string }> {
  return apiFetch(`/api/council-sessions/${sessionId}/deliberations`, {
    method: 'POST',
    body: JSON.stringify(body),
  })
}

export async function voteOnDeliberation(
  sessionId: string,
  deliberationId: string,
  body: { pour: number; contre: number; abstention: number },
): Promise<void> {
  await apiFetch(
    `/api/council-sessions/${sessionId}/deliberations/${deliberationId}/vote`,
    { method: 'POST', body: JSON.stringify(body) },
  )
}

export async function closeCouncilSession(sessionId: string): Promise<void> {
  await apiFetch(`/api/council-sessions/${sessionId}/close`, { method: 'POST' })
}
