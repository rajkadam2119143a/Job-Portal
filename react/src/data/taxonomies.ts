import type { Taxonomy } from "@/types";
import { jobs } from "./jobs";

const slugify = (s: string) =>
  s
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/(^-|-$)/g, "");

const countBy = (key: "category" | "department" | "location", name: string) =>
  jobs.filter((j) => j[key] === name && j.status === "Open").length;

const build = (
  key: "category" | "department" | "location",
  entries: [string, string][],
): Taxonomy[] =>
  entries.map(([name, description], i) => ({
    id: `${key}-${i + 1}`,
    slug: slugify(name),
    name,
    description,
    count: countBy(key, name),
    status: "Active" as const,
  }));

export const categories = build("category", [
  ["Technology", "Engineering, data and platform roles that build our products."],
  ["Product", "Product management and programme roles shaping what we build."],
  ["Design", "Product design, research and brand craft across every surface."],
  ["Marketing", "Growth, brand and content roles that grow our audience."],
  ["Finance", "Planning, reporting and controls that keep the business healthy."],
  ["Human Resources", "People, talent and culture roles across the organisation."],
  ["Operations", "Operational excellence, legal and service delivery roles."],
  ["Sales", "Revenue roles building long-term customer relationships."],
]);

export const departments = build("department", [
  ["Engineering", "Builds and runs the platforms behind every product experience."],
  ["Data & Analytics", "Turns data into insight, models and reliable pipelines."],
  ["Product", "Owns discovery, roadmap and outcomes for each product area."],
  ["Design", "Designs clear, accessible and human product experiences."],
  ["Marketing", "Tells our story and brings the right audience to our products."],
  ["Finance", "Plans, measures and safeguards the financial health of the company."],
  ["Human Resources", "Hires, develops and supports people across the company."],
  ["Operations", "Keeps daily delivery efficient, compliant and predictable."],
  ["Customer Success", "Supports customers and turns feedback into improvements."],
  ["Sales", "Partners with customers to solve problems with our products."],
  ["Legal", "Advises on contracts, privacy and regulatory obligations."],
]);

export const locations = build("location", [
  ["Mumbai", "Our headquarters, home to product, engineering and commercial teams."],
  ["Pune", "A growing engineering and operations hub with modern studio spaces."],
  ["Bengaluru", "Our largest technology centre, focused on platform and data."],
  ["Delhi", "Commercial, marketing and legal teams serving the north region."],
  ["Hyderabad", "Analytics, HR and business operations teams."],
  ["Remote", "Distributed roles open to candidates anywhere in India."],
]);

export const jobTypes = ["Full Time", "Part Time", "Contract", "Internship"];
export const workModes = ["Remote", "Hybrid", "Onsite"];
export const experienceLevels = ["0-2 Years", "2-5 Years", "5-8 Years", "8+ Years"];
export const datePostedOptions = [
  { label: "Any Time", value: "any" },
  { label: "Last 24 Hours", value: "1" },
  { label: "Last 7 Days", value: "7" },
  { label: "Last 14 Days", value: "14" },
  { label: "Last 30 Days", value: "30" },
];
