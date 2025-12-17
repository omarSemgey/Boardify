# Boardify

Boardify is a hybrid between Obsidian and Notion: a full-stack note-taking and collaboration platform.  
It combines Markdown note-linking with real-time multi-user collaboration and a robust role-based permissions system.

> ⚠️ Early development — main branch is currently empty. Active work happens in `dev-front` and `dev-back` branches.

---

## Overview

Boardify merges the best of two worlds:

- **Obsidian:** Markdown-based note-taking, internal note linking, and graph visualization  
- **Notion:** Real-time collaboration, multi-user editing, and online access  

It’s designed for complex workflows, team collaboration, and enterprise-like use cases.

---

## Current Milestones

- Core CRUD + Authentication (users, boards, categories, notes)  
- Realtime collaborative notes  
- Media & note linking  
- Board-level roles & permissions  
- Search, indexing, and caching  
- Version control for notes  
- Pre-made and custom themes  
- Obsidian-style note graph  

> Development continues — next steps include UX polish, theme marketplace, and advanced note features.

---

## Tech Stack

- **Frontend:** React, Next.js, TailwindCSS, TypeScript  
- **Backend:** Laravel (PHP), Node.js, Express  
- **Database:** MySQL (primary), SQLite (dev/test)  
- **Realtime Collaboration:** CRDT engine (Yjs / Automerge) + WebSockets  
- **Search & Indexing:** Meilisearch / Elastic / Laravel Scout  

---

## Status

This project is private and actively developed. It’s not open source.  
Pinned here to showcase my work and portfolio.
