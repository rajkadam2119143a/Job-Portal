import type { Application, ApplicationInput, ApplicationStatus } from "@/types";
import { db, delay } from "./store";

export interface ApplicationFilters {
  keyword?: string;
  status?: string;
  job?: string;
  department?: string;
  date?: string;
}

export async function getApplications(
  filters: ApplicationFilters = {},
): Promise<Application[]> {
  const kw = filters.keyword?.trim().toLowerCase();
  const items = db.applications.filter((a) => {
    if (kw && !`${a.candidateName} ${a.email} ${a.jobTitle} ${a.id}`.toLowerCase().includes(kw))
      return false;
    if (filters.status && a.status !== filters.status) return false;
    if (filters.job && a.jobSlug !== filters.job) return false;
    if (filters.department && a.department !== filters.department) return false;
    return true;
  });
  return delay(
    [...items].sort((a, b) => +new Date(b.appliedDate) - +new Date(a.appliedDate)),
  );
}

export async function getApplication(id: string): Promise<Application | null> {
  return delay(db.applications.find((a) => a.id === id) ?? null, 250);
}

export async function getApplicationStats() {
  const by = (s: ApplicationStatus) =>
    db.applications.filter((a) => a.status === s).length;
  return delay({
    total: db.applications.length,
    newApplications: by("New"),
    shortlisted: by("Shortlisted"),
    interview: by("Interview"),
    hired: by("Hired"),
    rejected: by("Rejected"),
  });
}

export async function updateApplicationStatus(
  id: string,
  status: ApplicationStatus,
): Promise<Application | null> {
  const idx = db.applications.findIndex((a) => a.id === id);
  if (idx === -1) return delay(null);
  db.applications[idx] = { ...db.applications[idx], status };
  return delay(db.applications[idx], 300);
}

export async function updateApplicationNotes(id: string, notes: string) {
  const idx = db.applications.findIndex((a) => a.id === id);
  if (idx === -1) return delay(null);
  db.applications[idx] = { ...db.applications[idx], notes };
  return delay(db.applications[idx], 300);
}

export async function submitApplication(
  input: ApplicationInput,
): Promise<{ applicationId: string }> {
  const id = `APP-2026-${String(200 + db.applications.length).padStart(5, "0")}`;
  const job = db.jobs.find((j) => j.slug === input.jobSlug);
  db.applications = [
    {
      id,
      candidateName: `${input.firstName} ${input.lastName}`.trim(),
      email: input.email,
      phone: input.phone,
      location: input.currentLocation,
      linkedin: input.linkedin,
      portfolio: input.portfolio,
      jobId: job?.jobId ?? "—",
      jobSlug: input.jobSlug,
      jobTitle: input.jobTitle,
      department: job?.department ?? "—",
      experience: input.yearsOfExperience ? `${input.yearsOfExperience} Years` : "—",
      currentCompany: input.currentCompany,
      noticePeriod: input.noticePeriod,
      coverLetter: input.coverLetter,
      resumeName: input.resumeName,
      status: "New",
      appliedDate: new Date().toISOString(),
      notes: "",
    },
    ...db.applications,
  ];
  return delay({ applicationId: id }, 900);
}
