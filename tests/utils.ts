import {Page} from "@playwright/test";

type Settings = {
    disable_rest_endpoints: boolean,
    disable_head_data: boolean,
    disable_plugin_update_mail: boolean,
    disable_pingbacks: boolean,
    disable_debug: boolean,
    disable_theme_update_mail: boolean,
    disable_wp_embed: boolean,
    disable_multisite_signup: boolean,
    disable_file_editor: boolean,
    disable_feeds: boolean,
    disable_wp_version: boolean,
    disable_wp_auto_update: boolean,
}

export const login = async (page: Page) => {
    await page.goto(process.env.WP_URL + '/wp-login.php');
    await page.fill('#user_login', process.env.WP_USERNAME);
    await page.fill('#user_pass', process.env.WP_PASSWORD);
    await page.click('#wp-submit');
    await page.waitForLoadState("networkidle");
}

export const activatePlugin = async (page: Page, name: string) => {
    await page.goto(process.env.WP_URL + '/wp-admin/plugins.php');
    await page.click(`#activate-${name}`);
}

export const isPluginActivated = async (page: Page, name: string) => {
    await page.goto(process.env.WP_URL + '/wp-admin/plugins.php');
    const activateButton = await page.$(`#activate-${name}`);
    return activateButton === null;
}

export const deactivatePlugin = async (page: Page, name: string) => {
    await page.goto(process.env.WP_URL + '/wp-admin/plugins.php');
    await page.click(`#deactivate-${name}`);
}

export const settingsPlugin = async (page: Page, settings: Partial<Settings>) => {
    await page.goto(process.env.WP_URL + "/wp-admin/options-general.php?page=simple-security-settings-page");

    for (const [setting, active] of Object.entries(settings)){
        const settingsCheckbox = await page.$('#' + setting);
        await settingsCheckbox.setChecked(active);
    }

    await page.click("#submit");
}