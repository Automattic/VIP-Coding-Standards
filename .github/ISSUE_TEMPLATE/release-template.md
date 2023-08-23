---
name: Release template
about: Internally used for new releases
title: Release x.y.z
labels: 'Type: Maintenance'
assignees: GaryJones, rebeccahum

---

:warning: DO NOT MERGE (YET) :warning:

[Remaining work for this Milestone](https://github.com/Automattic/VIP-Coding-Standards/milestone/X)

PR for tracking changes for the X.Y.Z release. Target release date: DOW DD MMMM YYYY.

- [ ] Scan WordPress (or just wp-admin folder) with prior version and compare results against new release for potential new bugs.
- [ ] Add change log for this release: PR #XXX
- [ ] Double-check whether any dependencies need bumping.
- [ ] Merge this PR.
- [ ] Add signed release tag against `main`.
- [ ] Close the current milestone.
- [ ] Open a new milestone for the next release.
- [ ] If any open PRs/issues which were milestoned for this release do not make it into the release, update their milestone.
- [ ] Write a Lobby post.
- [ ] Write an internal P2 post.
- [ ] Open PR to update [Review Bot dependencies](https://github.com/Automattic/vip-go-ci/blob/master/tools-init.sh).
