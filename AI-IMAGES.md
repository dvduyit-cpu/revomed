# REVOMED medical image collection

Created with the built-in imagegen tool on 2026-11-08. Original conceptual illustrations; the people and laboratory are fictional. No AMS assets or logos were copied. Existing REVOMED branding is retained.

Final web assets live in `public/images/ai/`. The original six images use WebP quality 88; the additional light anatomy background uses quality 90. Seven files total approximately 815 KB. Editable image selections use copies under `storage/app/public/media/ai/`.

## Generation prompt set

All prompts requested original images, blue-white medical technology styling, no text, no logos, no watermarks and no collage.

- `heart-hero.webp`: Premium 16:9 medical technology hero. Translucent glass-like human upper torso with luminous anatomically plausible heart and branching coronary vessels on the right. Sapphire/cyan rim light; navy #103f6c background; calm left-side negative space for HTML headings. Photorealistic 3D scientific visualization, no gore.
- `coronary-stent.webp`: Landscape 3:2 photorealistic macro CGI of a coronary metallic mesh stent expanded on a translucent angioplasty balloon. Polished cobalt chrome lattice, rounded struts, blue balloon and slender catheter shaft. Diagonal composition, pale ice-blue seamless studio background, soft shadows, generous margins, unbranded conceptual device.
- `balloon-catheter.webp`: Landscape 3:2 CGI of a clear inflated angioplasty balloon with tapered shoulders, blue inner shaft and platinum marker bands. No stent mesh. Flexible tubing curves toward the lower right. Pale icy blue studio background, transparent polymer materials, softbox lighting, unbranded conceptual device.
- `coronary-accessories.webp`: Landscape 3:2 studio CGI still life: guidewire in a loose circle, clear Y connector, introducer sheath with blue hub and clear inflation syringe with blue handle. Distinct, orderly objects on an icy blue seamless background, transparent polymers, subtle shadows. Unbranded conceptual devices.
- `research-laboratory.webp`: Widescreen 16:9 architectural photograph of a fictional modern medical device research laboratory. Blue-white workbench, microscope, precision instruments, glass partition, tall windows and cool natural daylight. No people; calm left-side space for headings; realistic 35mm photography.
- `research-team.webp`: Landscape 3:2 candid editorial photograph of four fictional Vietnamese medical technology professionals, two women and two men aged 28–48. White lab coats over navy clothing, collaborating around a catheter at a workbench. Natural expressions, bright blue-white laboratory, soft daylight, realistic hands and faces. No badges or branding.

## Installation on another environment

After deploying the code and assets, run:

```sh
php artisan website:replace-images --purge-old-images
php artisan view:clear
```

This replaces page image selections, product primary/gallery images, article covers and job images. Product text, publication states and ordering are preserved. It removes old raster images from the public media library while preserving branding, the selected logo and non-image documents. Metadata is saved in private `deployment/backups/images-*.json` before replacement. Removed image binaries are not backed up. Without `--purge-old-images`, old files remain.

The old bundled healthcare, cardiovascular collage and Hoang Thuoc logo images were removed from the source. All active fallback references use the new collection or the REVOMED SVG logo.

## Light anatomy homepage background

`public/images/ai/vascular-anatomy-hero.webp` was generated with the built-in imagegen tool using the user's full-body vascular illustration as a visual reference. WebP quality 90, approximately 103 KB.

Generation prompt: Original 16:9 medical website background; full-length translucent navy human body with red arteries, blue veins and visible heart, including the head and feet. Place the figure in the right third, at 90% of image height, leaving the left 55% clear for HTML text. White to pale icy-grey backdrop, faint polygon mesh and connecting nodes, subtle blue and coral accents. Premium scientific 3D visualization. No text, anatomical labels, callout circles, branding, watermark or gore.

To apply this homepage image on an existing hosting installation without replacing other images or copy:

```sh
php artisan website:install-home-background
php artisan view:clear
```

Deploy `public/images/ai/vascular-anatomy-hero.webp` and the updated CSS to the domain's public directory as well.
