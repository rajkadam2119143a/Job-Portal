import type { Application, ApplicationStatus } from "@/types";
import { jobs } from "./jobs";

const names = [
  "Raj Kadam",
  "Ananya Sharma",
  "Vikram Iyer",
  "Meera Nair",
  "Rahul Deshpande",
  "Sneha Patil",
  "Arjun Menon",
  "Priya Krishnan",
  "Karan Malhotra",
  "Divya Rao",
  "Aditya Joshi",
  "Fatima Sheikh",
  "Nikhil Verma",
  "Ishita Banerjee",
  "Sanjay Pillai",
  "Neha Kulkarni",
  "Rohan Gupta",
  "Tanvi Shah",
  "Imran Qureshi",
  "Pooja Reddy",
  "Aakash Bhatia",
  "Lakshmi Subramanian",
  "Yash Agarwal",
  "Ritu Chawla",
];

const statuses: ApplicationStatus[] = [
  "New",
  "New",
  "Under Review",
  "Shortlisted",
  "Interview",
  "Offer",
  "Hired",
  "Rejected",
];

const companies = [
  "Northwind Digital",
  "Bluepeak Systems",
  "Corevault Labs",
  "Meridian Tech",
  "Orbit Retail",
  "Lightfold Media",
  "Sundial Analytics",
];

const notice = ["Immediate", "15 Days", "30 Days", "60 Days", "90 Days"];

const applied = (n: number) => {
  const d = new Date("2026-09-02T09:00:00.000Z");
  d.setDate(d.getDate() - n);
  return d.toISOString();
};

export const applications: Application[] = names.map((name, i) => {
  const job = jobs[(i * 3) % jobs.length];
  const first = name.split(" ")[0].toLowerCase();
  return {
    id: `APP-2026-${String(124 + i).padStart(5, "0")}`,
    candidateName: name,
    email: `${first}.${name.split(" ")[1].toLowerCase()}@example.com`,
    phone: `+91 98${String(10000000 + i * 137911).slice(0, 8)}`,
    location: job.location === "Remote" ? "Nagpur" : job.location,
    linkedin: `https://linkedin.com/in/${first}-${i + 1}`,
    portfolio: i % 3 === 0 ? `https://${first}.design` : undefined,
    jobId: job.jobId,
    jobSlug: job.slug,
    jobTitle: job.title,
    department: job.department,
    experience: `${2 + (i % 9)} Years`,
    currentCompany: companies[i % companies.length],
    noticePeriod: notice[i % notice.length],
    coverLetter: `I have been following the work your ${job.department} team is doing and would welcome the chance to contribute as a ${job.title}. In my current role I have delivered comparable work end to end, and I am particularly interested in the ownership this position offers.`,
    resumeName: `${first}-resume.pdf`,
    status: statuses[i % statuses.length],
    appliedDate: applied(i % 14),
    notes: i % 4 === 0 ? "Strong portfolio. Schedule an intro call." : "",
  };
});
