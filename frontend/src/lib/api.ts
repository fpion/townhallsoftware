import type { CouncilSessionView, CouncilSessionSummaryView, TownHallView, CouncilorView } from './types'

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

// ── Town halls ──────────────────────────────────────────────────────────────

export async function listTownHalls(): Promise<TownHallView[]> {
  return apiFetch('/api/listTownHalls')
}

export async function createTownHall(body: {
  code: string
  name: string
  street: string
  city: string
  postalCode: string
  population: number
}): Promise<void> {
  await apiFetch('/api/createTownHall', { method: 'POST', body: JSON.stringify(body) })
}

// ── Councilors ──────────────────────────────────────────────────────────────

export async function listCouncilors(): Promise<CouncilorView[]> {
  return apiFetch('/api/listCouncilors')
}

export async function createCouncilor(body: {
  firstName: string
  lastName: string
  email: string
}): Promise<{ id: string }> {
  return apiFetch('/api/createCouncilor', { method: 'POST', body: JSON.stringify(body) })
}

export async function assignCouncilorRole(
  councilorId: string,
  body: { townHallCode: string; role: string },
): Promise<void> {
  await apiFetch(`/api/assignCouncilorRole/${councilorId}`, {
    method: 'POST',
    body: JSON.stringify(body),
  })
}

// ── Council sessions ────────────────────────────────────────────────────────

export async function listCouncilSessions(townHallCode: string): Promise<CouncilSessionSummaryView[]> {
  return apiFetch(`/api/listCouncilSessions?townHallCode=${encodeURIComponent(townHallCode)}`)
}

export async function getCouncilSession(id: string): Promise<CouncilSessionView> {
  return apiFetch(`/api/getCouncilSession/${id}`)
}

export async function createCouncilSession(body: {
  townHallCode: string
  date: string
  orderOfBusiness: string
  exceptional?: boolean
}): Promise<{ id: string }> {
  return apiFetch('/api/createCouncilSession', {
    method: 'POST',
    body: JSON.stringify(body),
  })
}

export async function sendCouncilSessionInvitations(sessionId: string): Promise<void> {
  await apiFetch(`/api/sendCouncilSessionInvitations/${sessionId}`, { method: 'POST' })
}

export async function registerAttendance(
  sessionId: string,
  body: { councilorId: string; status: string; proxyHolderId?: string },
): Promise<void> {
  await apiFetch(`/api/registerAttendance/${sessionId}`, {
    method: 'POST',
    body: JSON.stringify(body),
  })
}

export async function openCouncilSession(sessionId: string): Promise<void> {
  await apiFetch(`/api/openCouncilSession/${sessionId}`, { method: 'POST' })
}

export async function addDeliberation(
  sessionId: string,
  body: { title: string; description: string },
): Promise<{ id: string }> {
  return apiFetch(`/api/addDeliberation/${sessionId}`, {
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
    `/api/voteOnDeliberation/${sessionId}/${deliberationId}`,
    { method: 'POST', body: JSON.stringify(body) },
  )
}

export async function closeCouncilSession(sessionId: string): Promise<void> {
  await apiFetch(`/api/closeCouncilSession/${sessionId}`, { method: 'POST' })
}
