import config from '../config/config';
const title = config.title + ' ' + config.subheading + '-' + config.description;

export default function getPageTitle(pageTitle) {
  if (pageTitle) {
    return `${pageTitle} - ${title}`
  }
  return `${title}`
}
