---
name: Release template
about: Internally used for new releases
title: Release 2.x.y
labels: 'Type: Maintenance'
assignees: GaryJones, rebeccahum

---

⚠️ DO NOT MERGE (YET) ⚠️

[Remaining work for this Milestone](https://github.com/Automattic/VIP-Coding-Standards/milestone/X)

PR for tracking changes for the X.Y.Z release. Target release date: DOW DD MMMM YYYY.

- [ ] Add change log for this release: PR #XXX
- [ ] Merge this PR.
- [ ] Add signed release tag against `master`.
- [ ] Close the current milestone.
- [ ] Open a new milestone for the next release.
- [ ] If any open PRs/issues which were milestoned for this release do not make it into the release, update their milestone.
- [ ] Write a Lobby post.
- [ ] Write an internal P2 post.
- [ ] Open PR to update [Review Bot dependencies](https://github.com/Automattic/vip-go-ci/blob/master/tools-init.sh).
