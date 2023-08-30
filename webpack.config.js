const { assertSupportedNodeVersion } = require('../src/Engine');

module.exports = async () => {
  process.noDeprecation = true; // Disables deprecation warnings

  assertSupportedNodeVersion(); // Asserts that the current Node version is supported by Laravel Mix

  const mix = require('../src/Mix').primary; // Requires Laravel Mix

  require(mix.paths.mix()); // Requires the Mix configuration file

  await mix.installDependencies(); // Installs project dependencies defined in the Mix configuration file
  await mix.init(); // Initializes Laravel Mix

  return mix.build(); // Builds the assets using Laravel Mix
};
