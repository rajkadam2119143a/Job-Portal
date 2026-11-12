import type { Job, JobFilters, Paginated } from "@/types";
import { db, delay } from "./store";
import { slugify } from "@/lib/format";

const matches = (job: Job, f: JobFilters) => {
  if (job.status === "Draft") return false;
  const kw = f.keyword?.trim().toLowerCase();
  if (kw) {
    const haystack = [
      job.title,
      job.department,
      job.category,
      job.location,
      job.shortDescription,
      ...job.skills,
    ]
      .join(" ")
      .toLowerCase();
    if (!haystack.includes(kw)) return false;
  }
  if (f.location && slugify(job.location) !== slugify(f.location)) return false;
  if (f.department && slugify(job.department) !== slugify(f.department)) return false;
  if (f.category && slugify(job.category) !== slugify(f.category)) return false;
  if (f.jobType && slugify(job.jobType) !== slugify(f.jobType)) return false;
  if (f.workMode && slugify(job.workMode) !== slugify(f.workMode)) return false;
  if (f.experience && slugify(job.experience) !== slugify(f.experience)) return false;
  if (f.datePosted && f.datePosted !== "any") {
    const days = Number(f.datePosted);
    const age =
      (new Date("2026-09-02T12:00:00.000Z").getTime() -
        new Date(job.postedDate).getTime()) /
      86400000;
    if (Number.isFinite(days) && age > days) return false;
  }
  return true;
};

export async function getJobs(filters: JobFilters = {}): Promise<Paginated<Job>> {
  const perPage = filters.perPage ?? 10;
  const page = Math.max(1, filters.page ?? 1);
  const filtered = db.jobs.filter((j) => matches(j, filters));

  const sorted = [...filtered].sort((a, b) => {
    if (filters.sort === "oldest")
      return +new Date(a.postedDate) - +new Date(b.postedDate);
    if (filters.sort === "title") return a.title.localeCompare(b.title);
    return +new Date(b.postedDate) - +new Date(a.postedDate);
  });

  const total = sorted.length;
  const totalPages = Math.max(1, Math.ceil(total / perPage));
  const safePage = Math.min(page, totalPages);
  return delay({
    items: sorted.slice((safePage - 1) * perPage, safePage * perPage),
    pagination: { page: safePage, perPage, total, totalPages },
  });
}

export async function getJob(slug: string): Promise<Job | null> {
  return delay(db.jobs.find((j) => j.slug === slug) ?? null, 250);
}

export async function getJobById(id: string): Promise<Job | null> {
  return delay(db.jobs.find((j) => j.id === id) ?? null, 250);
}

export async function getFeaturedJobs(limit = 4): Promise<Job[]> {
  return delay(db.jobs.filter((j) => j.featured).slice(0, limit));
}

export async function getLatestJobs(limit = 6): Promise<Job[]> {
  return delay(
    [...db.jobs]
      .sort((a, b) => +new Date(b.postedDate) - +new Date(a.postedDate))
      .slice(0, limit),
  );
}

export async function getRelatedJobs(slug: string, limit = 3): Promise<Job[]> {
  const job = db.jobs.find((j) => j.slug === slug);
  if (!job) return delay([]);
  const scored = db.jobs
    .filter((j) => j.slug !== slug)
    .map((j) => ({
      job: j,
      score:
        (j.department === job.department ? 3 : 0) +
        (j.category === job.category ? 2 : 0) +
        (j.location === job.location ? 1 : 0),
    }))
    .filter((s) => s.score > 0)
    .sort((a, b) => b.score - a.score);
  return delay(scored.slice(0, limit).map((s) => s.job));
}

export async function getJobStats() {
  return delay({
    total: db.jobs.length,
    open: db.jobs.filter((j) => j.status === "Open").length,
    draft: db.jobs.filter((j) => j.status === "Draft").length,
    closed: db.jobs.filter((j) => j.status === "Closed").length,
  });
}

export async function createJob(data: Partial<Job>): Promise<Job> {
  const title = data.title || "Untitled Role";
  const job: Job = {
    id: `job-${Date.now()}`,
    slug: slugify(`${title}-${data.location ?? "remote"}`),
    title,
    jobId: data.jobId || `JR${Math.floor(40000 + Math.random() * 9999)}`,
    department: data.department || "Engineering",
    category: data.category || "Technology",
    location: data.location || "Remote",
    country: "India",
    workMode: data.workMode || "Hybrid",
    jobType: data.jobType || "Full Time",
    experience: data.experience || "2-5 Years",
    postedDate: new Date().toISOString(),
    deadline: data.deadline || new Date(Date.now() + 2592000000).toISOString(),
    featured: data.featured ?? false,
    status: data.status || "Open",
    applications: 0,
    shortDescription: data.shortDescription || "",
    description: data.description || "",
    responsibilities: data.responsibilities || [],
    qualifications: data.qualifications || [],
    skills: data.skills || [],
    goodToHave: data.goodToHave || [],
    benefits: data.benefits || [],
    additionalInformation: data.additionalInformation || "",
  };
  db.jobs = [job, ...db.jobs];
  return delay(job, 400);
}

export async function updateJob(id: string, data: Partial<Job>): Promise<Job | null> {
  const idx = db.jobs.findIndex((j) => j.id === id);
  if (idx === -1) return delay(null);
  db.jobs[idx] = { ...db.jobs[idx], ...data };
  return delay(db.jobs[idx], 400);
}

export async function deleteJob(id: string): Promise<boolean> {
  db.jobs = db.jobs.filter((j) => j.id !== id);
  return delay(true, 300);
}

export async function duplicateJob(id: string): Promise<Job | null> {
  const job = db.jobs.find((j) => j.id === id);
  if (!job) return delay(null);
  const copy: Job = {
    ...job,
    id: `job-${Date.now()}`,
    slug: `${job.slug}-copy`,
    title: `${job.title} (Copy)`,
    status: "Draft",
    applications: 0,
    postedDate: new Date().toISOString(),
  };
  db.jobs = [copy, ...db.jobs];
  return delay(copy, 300);
}
