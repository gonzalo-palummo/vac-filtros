import themeConfig, { colors } from '@/../themeConfig.js';

let userInfo = undefined;
if (JSON.parse(localStorage.getItem('userInfo'))) {
  userInfo = JSON.parse(localStorage.getItem('userInfo'));
} else {
  userInfo = {
    displayName: null,
    token: null,
    userRole: 'anonymous',
  };

  localStorage.setItem('userInfo', JSON.stringify(userInfo));
}

const getUserInfo = () => {
  return userInfo;
};

// Check if device is touch device
// This is used to remove perfect scrollbar from touch devices
// Using Dynamic components
const is_touch_device = () => {
  const prefixes = ' -webkit- -moz- -o- -ms- '.split(' ');
  const mq = function(query) {
    return window.matchMedia(query).matches;
  };

  if ('ontouchstart' in window || window.DocumentTouch) {
    return true;
  }

  // include the 'heartz' as a way to have a non matching MQ to help terminate the join
  // https://git.io/vznFH
  const query = ['(', prefixes.join('touch-enabled),('), 'heartz', ')'].join(
    ''
  );
  return mq(query);
};

// /////////////////////////////////////////////
// State
// /////////////////////////////////////////////

const state = {
  AppActiveUser: getUserInfo(),
  bodyOverlay: false,
  isVerticalNavMenuActive: true,
  is_touch_device: is_touch_device(),
  mainLayoutType: themeConfig.mainLayoutType || 'vertical',
  reduceButton: themeConfig.sidebarCollapsed,
  verticalNavMenuWidth: 'default',
  verticalNavMenuItemsMin: false,
  scrollY: 0,
  theme: themeConfig.theme || 'light',
  themePrimaryColor: colors.primary,

  // Can be used to get current window with
  // Note: Above breakpoint state is for internal use of sidebar & navbar component
  windowWidth: null,
};

export default state;
