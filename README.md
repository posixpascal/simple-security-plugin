<center>
<img src=".github/images/logo.webp" width="256">

# Simple Security Plugin (SSP)


**Simple Security Plugin (SSP)** is a lightweight and easy-to-use WordPress plugin that helps you secure your WordPress installation by disabling or customizing various features that may expose your site to vulnerabilities. The plugin gives you fine-grained control over REST API endpoints, head data, automatic updates, and more.


</center>

## Features

- 👩‍👧 **Disable REST API Endpoints**: Prevents user enumeration and hides sensitive data exposed by the WordPress REST API.
- 🫥 **Remove Meta Data from Header**: Cleans up unnecessary meta tags in the HTML header (like RSD and WLW links) to avoid information leakage.
- 📧 **Disable Plugin and Theme Update Notifications**: Stops email notifications for plugin and theme updates to reduce email clutter.
- 🔄 **Disable Pingbacks and Trackbacks**: Protects your site from common XML-RPC DDoS attacks by disabling pingbacks.
- 🪖 **Disable Debug Mode**: Turns off WordPress debug mode to prevent sensitive error messages from being exposed.
- 📝 **Disable File Editors**: Blocks access to plugin and theme editors in the WordPress admin panel to prevent unauthorized code edits.
- 📰 **Disable RSS and Atom Feeds**: Stops WordPress from generating RSS and Atom feeds to limit content scraping and unwanted access to feed data.
- 📦 **Remove WP Embed**: Disables WordPress' default embed functionality to prevent content injection via oEmbed.
- *️⃣ **Remove WP Version Info**: Hides the WordPress version from your site's HTML to reduce the risk of targeted attacks based on known vulnerabilities.

## Installation

1. **Download** the plugin from GitHub or the WordPress plugin repository (if available).
2. **Upload** the plugin to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
3. **Activate** the plugin through the 'Plugins' screen in WordPress.
4. **Configure** the plugin by going to the **Settings** -> **Simple Security Plugin** page in your WordPress admin dashboard.

## Requirements

- WordPress 6.0 or higher
- PHP 8.1 or higher

## Usage

Once the plugin is activated, you can configure the security options from the **Simple Security Plugin** settings page.

### Available Settings:

- **Disable REST Endpoints**: Disable sensitive endpoints in the WordPress REST API.
- **Remove Head Data**: Remove unnecessary meta tags from the head section.
- **Disable Plugin Update Mail**: Stop receiving email notifications for plugin updates.
- **Disable Pingbacks**: Block XML-RPC pingbacks.
- **Disable Debug Mode**: Prevent error messages from being exposed publicly.
- **Disable File Editors**: Remove access to plugin and theme editors in the admin dashboard.
- **Disable RSS Feeds**: Disable RSS and Atom feeds.
- **Remove WP Embed**: Remove WordPress embed functionality.
- **Remove WP Version Hints**: Hide WordPress version information from the site's HTML.
- **Deactivate WP Auto Update**: Turn off WordPress automatic updates.

## Contributing

We welcome contributions to improve the plugin or add new features!

1. Fork the repository.
2. Create a new branch (`git checkout -b feature/my-feature`).
3. Commit your changes (`git commit -am 'Add new feature'`).
4. Push to the branch (`git push origin feature/my-feature`).
5. Create a new pull request.

## License

This plugin is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Support

If you encounter any issues or have suggestions, feel free to open an issue in the GitHub repository.
