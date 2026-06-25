# Staff User Importer — technical exercise

Thanks for taking the time to do this. It’s designed to take **about 2–4 hours**. We’re not
looking for a polished product — we’re interested in how you read unfamiliar code, isolate a
problem, and make focused changes across a Vue front end and a GraphQL/PHP back end.

## The scenario

“AcmeLearn” runs a learning platform. Each night, HR exports a CSV of staff and we import it
into the platform’s user store (a SQLite database, to keep this exercise self-contained). This
repository contains a small web app for doing that import: a **Vue 3 single-page app** that
talks to a **GraphQL API** in PHP, which in turn drives the import pipeline.

Each row in the export carries an `hr_id` — the employee’s identifier in the HR system the data
comes from. The platform’s own user store assigns its own surrogate `id` on insert; `hr_id` is
kept as the stable reference back to HR.

You don’t need any prior knowledge of our products to do this exercise.

## What’s in the box

```
api/                       GraphQL API layer (thin glue over the pipeline)
  bootstrap.php            Autoloading + the SQLite connection
  graphql/schema.graphql   The GraphQL schema (SDL)
  Schema.php               Builds the schema and dispatches to resolvers by name
  resolvers/               One file per operation (query/, mutation/)
  UserMapper.php           Maps store rows <-> GraphQL fields
public/
  router.php               Entry point for the PHP built-in server
  graphql.php              The /graphql endpoint
src/                       The import pipeline (plain PHP, independent of the API)
  CsvReader.php             Reads the CSV into rows
  UserValidator.php         Validates a single row
  UserRepository.php        Reads/writes users in the store
  ImportRunner.php          Orchestrates the import and builds the summary
  ImportSummary.php         Collects results for the report
client/                    Vue 3 SPA (Vite)
  src/views/               Import and Users pages
  src/components/           UserTable, ResultsSummary
  src/composables/          useGraphql (useQuery / useMutation)
tests/                     PHPUnit test suite
data/users.csv             A sample HR export
schema.sql                 The user-store schema
```

## Requirements

- PHP 8.1+ with the PDO SQLite extension (`pdo_sqlite`)
- Composer
- Node 18+ — needed for front-end development (`npm run dev`) and the Vue tests. The app can be
  **demoed** with PHP alone if `client/dist` is present (it’s committed for convenience).

## Running it

```bash
# 1. Install PHP dependencies
composer install

# 2. Build the front end (first time, or after changing client/ code)
cd client && npm install && npm run build && cd ..

# 3. Serve the app + API on one origin
php -S localhost:8080 public/router.php
# open http://localhost:8080
```

While working on the front end, run the Vite dev server for hot reload — it proxies `/graphql`
to the PHP server, so keep the PHP server running too:

```bash
cd client && npm run dev          # http://localhost:5173
```

The store is written to `data/users.sqlite`. **Delete that file to start from a clean store.**

## Running the tests

```bash
composer install && ./vendor/bin/phpunit      # PHP / API
cd client && npm install && npm run test      # Vue components + composable
```

## Your tasks

### 1. Fix the broken import (required)

QA have filed a ticket:

> Importing `data/users.csv` through the app fails partway through with a database error, and at
> least one member of staff from the file never makes it into the store. The data in the CSV looks
> fine to us, and the GraphQL response just shows a database error.

Running the PHP test suite, you’ll find one failing test that reproduces this
(`ImportRunnerTest::testEveryValidRowIsPersisted`). Track down the **root cause**, fix it, and get
the suite green. In your write-up, briefly explain what was actually going wrong. Resist the urge
to paper over it (swallowing the exception, `INSERT OR IGNORE`, de-duplicating rows) — the missing
member of staff must actually end up in the store.

### 2. Support updating users (required)

Right now the importer can only ever *create* users, and there’s no way to correct a user once
they’re imported.

- **(a) Make the import an upsert.** If a member of staff is already in the store, re-running the
  import should **update** them in place rather than failing; new staff are still created. The
  results summary should distinguish records that were **created** from those that were
  **updated**.
- **(b) Add an “edit user” flow to the SPA** that calls the `updateUser` mutation and reflects the
  change in the list. Add a new page, an inline editor — whatever you think fits best.

The GraphQL schema already declares `updated` and the `updateUser` mutation. Wire them through the
resolvers, the existing `UserRepository` / `ImportSummary`, and the UI. Add or adjust PHPUnit and
Vue tests as you see fit.

### 3. Handling a very large file (optional — if you have time)

The app base64-encodes the whole file into a single GraphQL request, and `CsvReader` reads the
entire CSV into memory before processing it. Imagine the nightly export grows to several hundred
thousand rows. How would you change the **transport** and the **pipeline** to handle that without
exhausting memory or timing out? A written explanation is fine if you don’t have time to implement
it; partial code plus notes is also welcome.

## Submitting

Please send us a git repository (with your commit history) codebase, plus a short note covering:

- the root cause of the bug in task 1, and how you fixed it;
- the approach you took for task 2 and any trade-offs;
- your thinking on task 3;
- anything you’d do with more time, or chose deliberately not to do.

We value clear, minimal changes that fit the existing code over large rewrites.
