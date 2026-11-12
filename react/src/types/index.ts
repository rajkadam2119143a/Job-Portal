export type JobType = "Full Time" | "Part Time" | "Contract" | "Internship";
export type WorkMode = "Remote" | "Hybrid" | "Onsite";
export type ExperienceLevel =
  | "0-2 Years"
  | "2-5 Years"
  | "5-8 Years"
  | "8+ Years";

export interface Job {
  id: string;
  slug: string;
  title: string;
  jobId: string;
  department: string;
  category: string;
  location: string;
  country: string;
  workMode: WorkMode;
  jobType: JobType;
  experience: ExperienceLevel;
  postedDate: string; // ISO
  deadline: string; // ISO
  featured: boolean;
  status: "Open" | "Draft" | "Closed";
  applications: number;
  shortDescription: string;
  description: string;
  responsibilities: string[];
  qualifications: string[];
  skills: string[];
  goodToHave: string[];
  benefits: string[];
  additionalInformation: string;
}

export interface JobFilters {
  keyword?: string;
  location?: string;
  department?: string;
  category?: string;
  jobType?: string;
  workMode?: string;
  experience?: string;
  datePosted?: string;
  sort?: "newest" | "oldest" | "title";
  page?: number;
  perPage?: number;
}

export interface Pagination {
  page: number;
  perPage: number;
  total: number;
  totalPages: number;
}

export interface Paginated<T> {
  items: T[];
  pagination: Pagination;
}

export interface Taxonomy {
  id: string;
  slug: string;
  name: string;
  description: string;
  count: number;
  status?: "Active" | "Inactive";
}

export type ApplicationStatus =
  | "New"
  | "Under Review"
  | "Shortlisted"
  | "Interview"
  | "Offer"
  | "Hired"
  | "Rejected";

export interface Application {
  id: string;
  candidateName: string;
  email: string;
  phone: string;
  location: string;
  linkedin?: string;
  portfolio?: string;
  jobId: string;
  jobSlug: string;
  jobTitle: string;
  department: string;
  experience: string;
  currentCompany: string;
  noticePeriod: string;
  coverLetter?: string;
  resumeName: string;
  status: ApplicationStatus;
  appliedDate: string;
  notes?: string;
}

export interface ApplicationInput {
  firstName: string;
  lastName: string;
  email: string;
  phone: string;
  currentLocation: string;
  resumeName: string;
  coverLetter: string;
  linkedin: string;
  portfolio: string;
  yearsOfExperience: string;
  currentCompany: string;
  noticePeriod: string;
  jobSlug: string;
  jobTitle: string;
}

export interface CareersSettings {
  companyName: string;
  companyLogo: string;
  hrEmail: string;
  notificationEmail: string;
  jobsPerPage: number;
  maxResumeSizeMb: number;
  allowedResumeTypes: string;
  notifyOnApplication: boolean;
  notifyWeeklyDigest: boolean;
  privacyPolicyUrl: string;
}
