---
subject: "Changelog v1.0.5 — LinkedIn PDFs, video, team roles, Discord posting & more"
---

# Changelog v1.0.5 — LinkedIn PDFs, video, team roles, Discord posting & more

By TryPost Product Team • [Release v1.0.5](https://github.com/trypostit/trypost/releases/tag/v1.0.5)

Hey! It's been a few weeks since the last roundup, so this one covers a bigger stretch. Here's everything that's shipped in TryPost.

## LinkedIn PDF carousels

You can now post swipeable PDF documents to LinkedIn, the same format people actually stop to read. Attach a PDF and TryPost picks the right post type for you, with no extra settings to fiddle with. You can also preview PDFs straight from the media gallery, so you don't have to open them in a new tab just to check you grabbed the right file.

## Better video publishing

Bluesky now handles video: upload a clip and it embeds natively in your post. We also made video uploads steadier everywhere. Large videos to X and LinkedIn were failing with memory errors, and that's sorted now. Pinterest video pins get a cover image on their own, and YouTube Shorts can't be scheduled without the text that becomes their title, so they stop failing at publish time.

## Team roles and client review

You can invite people into a workspace with a role that fits: Admin, Member, or Viewer. Viewers are read-only, but they can open any post and leave comments. This is the one I'm most excited about: hand a client or a manager a Viewer seat, let them review what's scheduled, and collect their feedback right on the post, with no risk of them changing something. Members handle the day-to-day, while connecting accounts and managing the team stays with Admins and the owner.

## Post straight to Discord, smarter automations

Discord is a channel now. Connect a server, pick the exact channel, add mentions, and check a live preview before it goes out, with Discord engagement flowing into your metrics like everything else. Automations got smarter too: they read both RSS and Atom feeds, and you can pull dynamic variables straight from a feed so each item templates itself instead of being copied by hand.

## AI content templates

Generating a post with AI now starts with picking a format: a single image card, a carousel, or a tweet-style card (there's even one with a verified badge, and one on a photo background). You choose the visual style on the same screen, and these templates show up inside automations too, so your scheduled content can use them.

## A smoother start

Signing up is quicker, with Google and GitHub front and center and your first workspace created for you. We now ask two quick things when you join: what best describes you, and what you're hoping to get done with TryPost. There are more ways to describe yourself too, with Developer, Marketer, and Online store added to the list. On the brand settings page, you can also pull your brand details straight from your website instead of typing them out.

## New features

- Post swipeable PDF carousels to LinkedIn, and preview PDFs without leaving the editor
- Upload and embed videos on Bluesky
- Invite clients or stakeholders as Viewers, who can review and comment but not edit
- Publish straight to Discord channels and groups
- AI post formats: image cards, carousels, and tweet-style cards
- RSS and Atom automations with dynamic variables pulled from the feed
- Tell us your goals at signup, plus new personas (Developer, Marketer, Online store)
- Fill your brand details from your website

## Fixes

- Large video uploads to X and LinkedIn no longer run out of memory
- Pinterest video pins publish with a cover image automatically
- YouTube Shorts can no longer be scheduled without their title text
- An image and a video can no longer be combined in the same post
- Per-platform settings (aspect ratio, TikTok privacy, Pinterest boards, Discord channel) now carry through the API and MCP, not just the app
- Telegram connection problems show a clear message instead of failing quietly

Questions or ideas? Come hang out in our [Discord](https://trypost.it/discord).

Cheers,
Paulo from TryPost.it
