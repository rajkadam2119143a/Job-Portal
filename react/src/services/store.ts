import { applications as seedApplications } from "@/data/applications";
import { jobs as seedJobs } from "@/data/jobs";
import { categories, departments, locations } from "@/data/taxonomies";
import type { Application, CareersSettings, Job, Taxonomy } from "@/types";

/**
 * In-memory mock store. This is the ONLY place mock data is mutated.
 * When the WordPress REST API is available, the service layer in this folder
 * can call `fetch()` instead of reading from this store — no UI change needed.
 */
export const db = {
  jobs: [...seedJobs] as Job[],
  applications: [...seedApplications] as Application[],
  categories: [...categories] as Taxonomy[],
  departments: [...departments] as Taxonomy[],
  locations: [...locations] as Taxonomy[],
  settings: {
    companyName: "Company Careers",
    companyLogo: "",
    hrEmail: "people@company.example",
    notificationEmail: "recruitment@company.example",
    jobsPerPage: 10,
    maxResumeSizeMb: 5,
    allowedResumeTypes: "pdf, doc, docx",
    notifyOnApplication: true,
    notifyWeeklyDigest: false,
    privacyPolicyUrl: "https://company.example/privacy",
  } as CareersSettings,
};

/** Simulated network latency so loading states are real. */
export const delay = <T,>(value: T, ms = 350): Promise<T> =>
  new Promise((resolve) => setTimeout(() => resolve(value), ms));
