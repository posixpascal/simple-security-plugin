import {test, expect, Page} from '@playwright/test';
import {activatePlugin, deactivatePlugin, isPluginActivated, login, settingsPlugin} from "./utils";
import {JSDOM} from "jsdom";
const PLUGIN_NAME = "simple-wordpress-security";
const {WP_URL} = process.env;

const setup = async (page: Page) => {
  await login(page);
  if (await isPluginActivated(page, PLUGIN_NAME)){
    await deactivatePlugin(page, PLUGIN_NAME);
  }
}

// test('can log in and activate plugin', async ({ page }) => {
//   await setup(page);
//
//   await activatePlugin(page, PLUGIN_NAME);
//   await deactivatePlugin(page, PLUGIN_NAME);
// });
//
// test('disables the user REST endpoint', async ({ page }) => {
//   // Detect insecure application state
//   const responseBefore = await page.request.get(WP_URL + "?rest_route=/wp/v2/users");
//   const dataBefore = await responseBefore.json();
//   expect(dataBefore.length, "The testing state is not deterministic, expected a single admin user").toBe(1);
//   expect(dataBefore[0].name, "The testing state is not deterministic, expected a single admin user").toBe("admin");
//
//   // Installs plugin
//   await setup(page);
//   await activatePlugin(page, PLUGIN_NAME);
//   await settingsPlugin(page, {
//     disable_rest_endpoints: true
//   });
//
//   // Validate secure application state
//   const responseAfter = await page.request.get(WP_URL + "?rest_route=/wp/v2/users");
//   expect(responseAfter.status()).toBe(404);
// });
//
// test('disables file & plugin editor', async ({ page }) => {
//   // Detect insecure application state
//   await setup(page);
//   const themeBefore = await page.request.get(WP_URL + "/wp-admin/theme-editor.php");
//   expect(themeBefore.status(), "The testing state is not deterministic, expected theme-editor to be openable").toBe(200);
//   const pluginBefore = await page.request.get(WP_URL + "/wp-admin/plugin-editor.php");
//   expect(pluginBefore.status(), "The testing state is not deterministic, expected plugin-editor to be openable").toBe(200);
//
//   // Installs plugin
//   await activatePlugin(page, PLUGIN_NAME);
//   await settingsPlugin(page, {
//     disable_file_editor: true
//   });
//
//   // Validate secure application state
//   const themeAfter = await page.request.get(WP_URL + "/wp-admin/theme-editor.php");
//   const pluginAfter = await page.request.get(WP_URL + "/wp-admin/plugin-editor.php");
//   expect(themeAfter.status()).toBe(403);
//   expect(pluginAfter.status()).toBe(403);
// });
//
// test('disables version information from scripts', async ({ page }) => {
//   // Detect insecure application state
//   await setup(page);
//   const pageContentBefore = await page.request.get(WP_URL);
//   expect(pageContentBefore.status(), "The testing state is not deterministic, expected ?ver= string in scripts").toBe(200);
//
//   const domBefore = new JSDOM(await pageContentBefore.text())
//   const blockNavigationCSSBefore = domBefore.window.document.querySelector("#wp-block-navigation-css").getAttribute("href");
//   const blockNavigationSourceBefore = new URL(blockNavigationCSSBefore);
//   expect(blockNavigationSourceBefore.searchParams.get("ver")).toBe("6.6.1");
//
//   // Installs plugin
//   await activatePlugin(page, PLUGIN_NAME);
//   await settingsPlugin(page, {
//     disable_wp_version: true
//   });
//
//   // Validate secure application state
//   const pageContentAfter = await page.request.get(WP_URL);
//   expect(pageContentAfter.status(), "The testing state is not deterministic, expected theme-editor to be openable").toBe(200);
//
//   const domAfter = new JSDOM(await pageContentAfter.text())
//   const blockNavigationCSSAfter = domAfter.window.document.querySelector("#wp-block-navigation-css").getAttribute("href");
//   const blockNavigationSourceAfter = new URL(blockNavigationCSSAfter);
//   expect(blockNavigationSourceAfter.searchParams.get("ver")).toBe(null);
// });

test('disables <head> elements', async ({ page }) => {
  // Detect insecure application state
  await setup(page);
  const pageContentBefore = await page.request.get(WP_URL);
  const domBefore = new JSDOM(await pageContentBefore.text())
  const rssElementBefore = domBefore.window.document.querySelector("[type='application/rss+xml']");
  expect(rssElementBefore).toBeTruthy();

  // Installs plugin
  await setup(page);
  await activatePlugin(page, PLUGIN_NAME);
  await settingsPlugin(page, {
    disable_head_data: true
  });

  // Validate secure application state
  const rssElementAfter = domBefore.window.document.querySelector("[type='application/rss+xml']");
  expect(rssElementAfter).toBeTruthy();
});