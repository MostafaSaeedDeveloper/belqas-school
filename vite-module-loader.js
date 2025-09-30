import fs from 'fs/promises';
import path from 'path';
import { pathToFileURL } from 'url';

async function collectModuleAssetsPaths(paths, modulesPath) {
  const modulesDirectory = path.join(__dirname, modulesPath);
  const moduleStatusesPath = path.join(__dirname, 'modules_statuses.json');

  try {
    await fs.access(modulesDirectory);
    await fs.access(moduleStatusesPath);
  } catch {
    return paths;
  }

  try {
    const moduleStatusesContent = await fs.readFile(moduleStatusesPath, 'utf-8');
    const moduleStatuses = JSON.parse(moduleStatusesContent);
    const moduleDirectories = await fs.readdir(modulesDirectory);

    for (const moduleDir of moduleDirectories) {
      if (moduleDir === '.DS_Store' || moduleStatuses[moduleDir] !== true) {
        continue;
      }

      const viteConfigPath = path.join(modulesDirectory, moduleDir, 'vite.config.js');

      try {
        await fs.access(viteConfigPath);
        const moduleConfigURL = pathToFileURL(viteConfigPath);
        const moduleConfig = await import(moduleConfigURL.href);

        if (moduleConfig.paths && Array.isArray(moduleConfig.paths)) {
          paths.push(...moduleConfig.paths);
        }
      } catch {
        // vite.config.js does not exist for this module, skip it gracefully
      }
    }
  } catch (error) {
    console.error(`Error reading module configurations: ${error}`);
  }

  return paths;
}

export default collectModuleAssetsPaths;
