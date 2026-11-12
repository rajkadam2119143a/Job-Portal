import type { ExperienceLevel, Job, JobType, WorkMode } from "@/types";

const daysAgo = (n: number) => {
  const d = new Date("2026-09-02T00:00:00.000Z");
  d.setDate(d.getDate() - n);
  return d.toISOString();
};

const daysAhead = (n: number) => {
  const d = new Date("2026-09-02T00:00:00.000Z");
  d.setDate(d.getDate() + n);
  return d.toISOString();
};

const slugify = (s: string) =>
  s
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/(^-|-$)/g, "");

interface Seed {
  title: string;
  department: string;
  category: string;
  location: string;
  workMode: WorkMode;
  jobType: JobType;
  experience: ExperienceLevel;
  posted: number;
  featured?: boolean;
  summary: string;
  focus: string;
  skills: string[];
}

const seeds: Seed[] = [
  {
    title: "Senior WordPress Developer",
    department: "Engineering",
    category: "Technology",
    location: "Mumbai",
    workMode: "Hybrid",
    jobType: "Full Time",
    experience: "2-5 Years",
    posted: 3,
    featured: true,
    summary:
      "Build and maintain scalable content platforms and custom plugins used by millions of readers every month.",
    focus: "custom WordPress platforms, plugin architecture and performance",
    skills: ["WordPress", "PHP", "MySQL", "REST API", "React", "Git"],
  },
  {
    title: "Frontend Engineer (React)",
    department: "Engineering",
    category: "Technology",
    location: "Bengaluru",
    workMode: "Remote",
    jobType: "Full Time",
    experience: "2-5 Years",
    posted: 1,
    featured: true,
    summary:
      "Craft accessible, high-performance interfaces for our customer-facing web products.",
    focus: "design systems, accessibility and front-end performance",
    skills: ["React", "TypeScript", "Tailwind CSS", "Testing Library", "Vite"],
  },
  {
    title: "Backend Engineer (Node.js)",
    department: "Engineering",
    category: "Technology",
    location: "Pune",
    workMode: "Hybrid",
    jobType: "Full Time",
    experience: "5-8 Years",
    posted: 6,
    summary:
      "Design resilient services and APIs that power our core transaction and content workflows.",
    focus: "distributed services, API design and reliability",
    skills: ["Node.js", "PostgreSQL", "Redis", "AWS", "Docker"],
  },
  {
    title: "Engineering Manager",
    department: "Engineering",
    category: "Technology",
    location: "Bengaluru",
    workMode: "Hybrid",
    jobType: "Full Time",
    experience: "8+ Years",
    posted: 12,
    summary:
      "Lead a cross-functional team of engineers delivering platform capabilities at scale.",
    focus: "team leadership, delivery planning and technical strategy",
    skills: ["People Management", "System Design", "Agile", "Hiring"],
  },
  {
    title: "QA Automation Engineer",
    department: "Engineering",
    category: "Technology",
    location: "Pune",
    workMode: "Onsite",
    jobType: "Full Time",
    experience: "2-5 Years",
    posted: 9,
    summary:
      "Own automated test coverage across web and API layers to keep releases predictable.",
    focus: "test automation, release quality and CI pipelines",
    skills: ["Playwright", "TypeScript", "CI/CD", "API Testing"],
  },
  {
    title: "DevOps Engineer",
    department: "Engineering",
    category: "Technology",
    location: "Remote",
    workMode: "Remote",
    jobType: "Full Time",
    experience: "5-8 Years",
    posted: 4,
    summary:
      "Automate infrastructure, deployments and observability for a growing product estate.",
    focus: "infrastructure as code, CI/CD and observability",
    skills: ["Terraform", "Kubernetes", "AWS", "Grafana", "GitHub Actions"],
  },
  {
    title: "Data Analyst",
    department: "Data & Analytics",
    category: "Technology",
    location: "Hyderabad",
    workMode: "Hybrid",
    jobType: "Full Time",
    experience: "0-2 Years",
    posted: 2,
    summary:
      "Turn product and marketing data into decisions the business can act on quickly.",
    focus: "reporting, experimentation analysis and dashboards",
    skills: ["SQL", "Python", "Looker", "Excel", "Statistics"],
  },
  {
    title: "Senior Data Engineer",
    department: "Data & Analytics",
    category: "Technology",
    location: "Bengaluru",
    workMode: "Hybrid",
    jobType: "Full Time",
    experience: "5-8 Years",
    posted: 15,
    summary:
      "Build reliable data pipelines that serve analytics, personalisation and reporting.",
    focus: "batch and streaming pipelines, data modelling and governance",
    skills: ["Spark", "Airflow", "dbt", "Snowflake", "Python"],
  },
  {
    title: "Machine Learning Engineer",
    department: "Data & Analytics",
    category: "Technology",
    location: "Remote",
    workMode: "Remote",
    jobType: "Contract",
    experience: "5-8 Years",
    posted: 20,
    summary:
      "Ship recommendation and ranking models from prototype to production.",
    focus: "applied ML, evaluation and production model serving",
    skills: ["Python", "PyTorch", "MLOps", "Feature Stores"],
  },
  {
    title: "Product Manager",
    department: "Product",
    category: "Product",
    location: "Mumbai",
    workMode: "Hybrid",
    jobType: "Full Time",
    experience: "5-8 Years",
    posted: 5,
    featured: true,
    summary:
      "Own the roadmap for a core product area, from discovery through measurable outcomes.",
    focus: "product discovery, prioritisation and outcome measurement",
    skills: ["Roadmapping", "User Research", "Analytics", "Stakeholder Management"],
  },
  {
    title: "Associate Product Manager",
    department: "Product",
    category: "Product",
    location: "Pune",
    workMode: "Onsite",
    jobType: "Full Time",
    experience: "0-2 Years",
    posted: 8,
    summary:
      "Support senior PMs across research, specification and release readiness.",
    focus: "requirement writing, competitive research and launch support",
    skills: ["Product Sense", "SQL", "Documentation", "Communication"],
  },
  {
    title: "Technical Program Manager",
    department: "Product",
    category: "Product",
    location: "Bengaluru",
    workMode: "Hybrid",
    jobType: "Full Time",
    experience: "8+ Years",
    posted: 22,
    summary:
      "Coordinate multi-team programmes and keep complex delivery on track.",
    focus: "programme planning, dependency management and reporting",
    skills: ["Programme Management", "Risk Management", "Jira", "Reporting"],
  },
  {
    title: "Senior Product Designer",
    department: "Design",
    category: "Design",
    location: "Mumbai",
    workMode: "Hybrid",
    jobType: "Full Time",
    experience: "5-8 Years",
    posted: 7,
    featured: true,
    summary:
      "Shape end-to-end product experiences, from early concepts to polished interfaces.",
    focus: "interaction design, prototyping and design systems",
    skills: ["Figma", "Prototyping", "Design Systems", "User Testing"],
  },
  {
    title: "UX Researcher",
    department: "Design",
    category: "Design",
    location: "Remote",
    workMode: "Remote",
    jobType: "Part Time",
    experience: "2-5 Years",
    posted: 11,
    summary:
      "Run qualitative and quantitative studies that guide product direction.",
    focus: "research planning, interviews and insight synthesis",
    skills: ["Interviewing", "Survey Design", "Synthesis", "Usability Testing"],
  },
  {
    title: "Visual Design Intern",
    department: "Design",
    category: "Design",
    location: "Pune",
    workMode: "Onsite",
    jobType: "Internship",
    experience: "0-2 Years",
    posted: 14,
    summary:
      "Support the brand team with campaign visuals, templates and social assets.",
    focus: "brand visuals, layout craft and asset production",
    skills: ["Figma", "Illustrator", "Typography", "Layout"],
  },
  {
    title: "Performance Marketing Manager",
    department: "Marketing",
    category: "Marketing",
    location: "Delhi",
    workMode: "Hybrid",
    jobType: "Full Time",
    experience: "5-8 Years",
    posted: 10,
    summary:
      "Own paid acquisition across channels with clear efficiency and growth targets.",
    focus: "paid media, attribution and budget efficiency",
    skills: ["Google Ads", "Meta Ads", "GA4", "Attribution", "SQL"],
  },
  {
    title: "Content Marketing Specialist",
    department: "Marketing",
    category: "Marketing",
    location: "Mumbai",
    workMode: "Hybrid",
    jobType: "Full Time",
    experience: "2-5 Years",
    posted: 18,
    summary:
      "Plan and produce content that builds organic reach and audience trust.",
    focus: "editorial planning, SEO content and distribution",
    skills: ["SEO", "Copywriting", "Content Strategy", "WordPress"],
  },
  {
    title: "SEO Analyst",
    department: "Marketing",
    category: "Marketing",
    location: "Remote",
    workMode: "Remote",
    jobType: "Contract",
    experience: "2-5 Years",
    posted: 25,
    summary:
      "Improve organic visibility through technical audits and content optimisation.",
    focus: "technical SEO, keyword strategy and reporting",
    skills: ["Technical SEO", "Ahrefs", "GSC", "Analytics"],
  },
  {
    title: "Brand Communications Lead",
    department: "Marketing",
    category: "Marketing",
    location: "Delhi",
    workMode: "Onsite",
    jobType: "Full Time",
    experience: "8+ Years",
    posted: 30,
    summary:
      "Lead brand narrative, PR and campaign storytelling across markets.",
    focus: "brand strategy, PR and campaign storytelling",
    skills: ["Brand Strategy", "PR", "Campaign Management", "Copy Review"],
  },
  {
    title: "Financial Analyst",
    department: "Finance",
    category: "Finance",
    location: "Mumbai",
    workMode: "Onsite",
    jobType: "Full Time",
    experience: "2-5 Years",
    posted: 13,
    summary:
      "Support budgeting, forecasting and business performance reviews.",
    focus: "FP&A, forecasting and management reporting",
    skills: ["Excel", "Financial Modelling", "Power BI", "Variance Analysis"],
  },
  {
    title: "Accounts Payable Executive",
    department: "Finance",
    category: "Finance",
    location: "Pune",
    workMode: "Onsite",
    jobType: "Full Time",
    experience: "0-2 Years",
    posted: 16,
    summary:
      "Process vendor invoices and payments with accuracy and strong controls.",
    focus: "invoice processing, reconciliation and vendor queries",
    skills: ["Tally", "SAP", "Reconciliation", "GST Basics"],
  },
  {
    title: "Finance Manager",
    department: "Finance",
    category: "Finance",
    location: "Bengaluru",
    workMode: "Hybrid",
    jobType: "Full Time",
    experience: "8+ Years",
    posted: 27,
    summary:
      "Partner with business leaders on planning, controls and profitability.",
    focus: "business partnering, controls and profitability analysis",
    skills: ["FP&A", "Controls", "Leadership", "Audit"],
  },
  {
    title: "Talent Acquisition Partner",
    department: "Human Resources",
    category: "Human Resources",
    location: "Mumbai",
    workMode: "Hybrid",
    jobType: "Full Time",
    experience: "2-5 Years",
    posted: 2,
    summary:
      "Own hiring pipelines end to end and deliver a great candidate experience.",
    focus: "full-cycle recruiting, sourcing and stakeholder management",
    skills: ["Sourcing", "ATS", "Interviewing", "Employer Branding"],
  },
  {
    title: "HR Business Partner",
    department: "Human Resources",
    category: "Human Resources",
    location: "Hyderabad",
    workMode: "Onsite",
    jobType: "Full Time",
    experience: "5-8 Years",
    posted: 19,
    summary:
      "Advise managers on performance, engagement and organisational design.",
    focus: "employee relations, performance and org design",
    skills: ["Employee Relations", "Performance Management", "Coaching"],
  },
  {
    title: "Learning & Development Specialist",
    department: "Human Resources",
    category: "Human Resources",
    location: "Remote",
    workMode: "Remote",
    jobType: "Part Time",
    experience: "2-5 Years",
    posted: 24,
    summary:
      "Design learning journeys that help teams build durable capability.",
    focus: "learning design, facilitation and capability frameworks",
    skills: ["Instructional Design", "Facilitation", "LMS", "Assessment"],
  },
  {
    title: "Operations Manager",
    department: "Operations",
    category: "Operations",
    location: "Delhi",
    workMode: "Onsite",
    jobType: "Full Time",
    experience: "5-8 Years",
    posted: 17,
    summary:
      "Run daily operations with a focus on service levels and cost efficiency.",
    focus: "process ownership, SLAs and continuous improvement",
    skills: ["Process Design", "Lean", "Vendor Management", "Reporting"],
  },
  {
    title: "Business Operations Associate",
    department: "Operations",
    category: "Operations",
    location: "Hyderabad",
    workMode: "Hybrid",
    jobType: "Full Time",
    experience: "0-2 Years",
    posted: 21,
    summary:
      "Support planning, reporting and cross-team coordination for operations.",
    focus: "operational reporting, coordination and documentation",
    skills: ["Excel", "SQL", "Documentation", "Coordination"],
  },
  {
    title: "Customer Support Specialist",
    department: "Customer Success",
    category: "Operations",
    location: "Remote",
    workMode: "Remote",
    jobType: "Full Time",
    experience: "0-2 Years",
    posted: 1,
    summary:
      "Help customers resolve issues quickly and feed insights back to product.",
    focus: "customer support, troubleshooting and escalation handling",
    skills: ["Zendesk", "Communication", "Troubleshooting", "Empathy"],
  },
  {
    title: "Customer Success Manager",
    department: "Customer Success",
    category: "Operations",
    location: "Bengaluru",
    workMode: "Hybrid",
    jobType: "Full Time",
    experience: "5-8 Years",
    posted: 26,
    summary:
      "Own retention and growth for a portfolio of enterprise accounts.",
    focus: "account health, renewals and executive relationships",
    skills: ["Account Management", "Renewals", "QBRs", "CRM"],
  },
  {
    title: "Enterprise Sales Manager",
    department: "Sales",
    category: "Sales",
    location: "Mumbai",
    workMode: "Hybrid",
    jobType: "Full Time",
    experience: "8+ Years",
    posted: 28,
    summary:
      "Drive new enterprise revenue through consultative, multi-stakeholder selling.",
    focus: "enterprise pipeline, negotiation and closing",
    skills: ["Enterprise Sales", "Negotiation", "CRM", "Forecasting"],
  },
  {
    title: "Inside Sales Executive",
    department: "Sales",
    category: "Sales",
    location: "Pune",
    workMode: "Onsite",
    jobType: "Full Time",
    experience: "0-2 Years",
    posted: 23,
    summary:
      "Qualify inbound demand and build pipeline for the field sales team.",
    focus: "lead qualification, outreach and pipeline hygiene",
    skills: ["Outbound", "CRM", "Communication", "Discovery Calls"],
  },
  {
    title: "Legal Counsel",
    department: "Legal",
    category: "Operations",
    location: "Delhi",
    workMode: "Hybrid",
    jobType: "Full Time",
    experience: "5-8 Years",
    posted: 29,
    summary:
      "Advise business teams on commercial contracts, privacy and compliance.",
    focus: "commercial contracting, privacy and risk advisory",
    skills: ["Contracts", "Privacy", "Compliance", "Negotiation"],
  },
];

export const jobs: Job[] = seeds.map((s, i) => {
  const slug = slugify(`${s.title}-${s.location}`);
  return {
    id: `job-${i + 1}`,
    slug,
    title: s.title,
    jobId: `JR${40600 + i * 7}`,
    department: s.department,
    category: s.category,
    location: s.location,
    country: s.location === "Remote" ? "India (Remote)" : "India",
    workMode: s.workMode,
    jobType: s.jobType,
    experience: s.experience,
    postedDate: daysAgo(s.posted),
    deadline: daysAhead(28 + (i % 5) * 7),
    featured: Boolean(s.featured),
    status: "Open",
    applications: 4 + ((i * 13) % 47),
    shortDescription: s.summary,
    description: `We are looking for a ${s.title} to join our ${s.department} team in ${s.location}. In this role you will focus on ${s.focus}, working closely with product, design and engineering partners to deliver work that reaches customers quickly and reliably.\n\nYou will be trusted with real ownership from day one. We care about clear thinking, thoughtful communication and a bias toward shipping improvements that can be measured.`,
    responsibilities: [
      `Own day-to-day delivery for ${s.focus.split(",")[0]} within the ${s.department} team.`,
      "Partner with cross-functional stakeholders to translate goals into a clear plan of work.",
      "Document decisions, trade-offs and outcomes so knowledge stays with the team.",
      "Identify recurring problems and propose durable improvements rather than one-off fixes.",
      "Contribute to reviews, planning sessions and quality checks across the team.",
    ],
    qualifications: [
      `${s.experience} of relevant professional experience in a comparable role.`,
      "A track record of delivering work in a collaborative, fast-moving environment.",
      "Strong written and verbal communication with technical and non-technical audiences.",
      "Comfort working with data to support decisions and measure impact.",
      "Bachelor's degree or equivalent practical experience.",
    ],
    skills: s.skills,
    goodToHave: [
      "Experience in a high-growth product organisation.",
      "Exposure to enterprise-scale processes and compliance requirements.",
      "Mentoring or knowledge-sharing experience within a team.",
    ],
    benefits: [
      "Comprehensive health insurance for you and your dependents.",
      "Annual learning budget for courses, certifications and conferences.",
      "Flexible working hours and a supportive leave policy.",
      "Performance-linked rewards and a transparent review process.",
      "Wellness programmes and employee assistance support.",
    ],
    additionalInformation:
      "We are an equal opportunity employer. All applications are reviewed by our recruitment team, and shortlisted candidates are contacted within two weeks. Reasonable accommodations are available at any stage of the hiring process — just let your recruiter know what you need.",
  };
});
