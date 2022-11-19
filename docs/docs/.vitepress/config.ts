import {defineConfig} from 'vitepress'

export default defineConfig({
  title: 'Code Field Plugin',
  description: 'Documentation for the Code Field plugin',
  base: '/docs/code-field/',
  lang: 'en-US',
  head: [
    ['meta', {content: 'https://github.com/nystudio107', property: 'og:see_also',}],
    ['meta', {content: 'https://twitter.com/nystudio107', property: 'og:see_also',}],
    ['meta', {content: 'https://youtube.com/nystudio107', property: 'og:see_also',}],
    ['meta', {content: 'https://www.facebook.com/newyorkstudio107', property: 'og:see_also',}],
  ],
  themeConfig: {
    socialLinks: [
      {icon: 'github', link: 'https://github.com/nystudio107'},
      {icon: 'twitter', link: 'https://twitter.com/nystudio107'},
    ],
    logo: '/resources/img/plugin-logo.svg',
    editLink: {
      pattern: 'https://github.com/nystudio107/craft-code-field/edit/develop-v3/docs/docs/:path',
      text: 'Edit this page on GitHub'
    },
    algolia: {
      appId: 'AE3HRUJFEW',
      apiKey: 'c5dcc2be096fff3a4714c3a877a056fa',
      indexName: 'codefield'
    },
    lastUpdatedText: 'Last Updated',
    sidebar: [],
    nav: [],
  },
});
