<?php
/**
 * @link https://github.com/creocoder/yii2-flysystem
 * @copyright Copyright (c) 2015 Alexander Kochetov
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace common\components;

use common\components\filesystem\FtpFilesystem;
use common\components\filesystem\LocalFilesystem;
use common\components\filesystem\SftpFilesystem;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem as NativeFilesystem;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\caching\Cache;

/**
 * Filesystem
 *
 * @method \League\Flysystem\FilesystemInterface addPlugin(\League\Flysystem\PluginInterface $plugin)
 * @method void assertAbsent(string $path)
 * @method void assertPresent(string $path)
 * @method boolean copy(string $path, string $newpath)
 * @method boolean createDir(string $dirname, array $config = null)
 * @method boolean delete(string $path)
 * @method boolean deleteDir(string $dirname)
 * @method \League\Flysystem\Handler get(string $path, \League\Flysystem\Handler $handler = null)
 * @method \League\Flysystem\AdapterInterface getAdapter()
 * @method \League\Flysystem\Config getConfig()
 * @method array|false getMetadata(string $path)
 * @method string|false getMimetype(string $path)
 * @method integer|false getSize(string $path)
 * @method integer|false getTimestamp(string $path)
 * @method string|false getVisibility(string $path)
 * @method array getWithMetadata(string $path, array $metadata)
 * @method boolean has(string $path)
 * @method array listContents(string $directory = '', boolean $recursive = false)
 * @method array listFiles(string $path = '', boolean $recursive = false)
 * @method array listPaths(string $path = '', boolean $recursive = false)
 * @method array listWith(array $keys = [], $directory = '', $recursive = false)
 * @method boolean put(string $path, string $contents, array $config = [])
 * @method boolean putStream(string $path, resource $resource, array $config = [])
 * @method string|false read(string $path)
 * @method string|false readAndDelete(string $path)
 * @method resource|false readStream(string $path)
 * @method boolean rename(string $path, string $newpath)
 * @method boolean setVisibility(string $path, string $visibility)
 * @method boolean update(string $path, string $contents, array $config = [])
 * @method boolean updateStream(string $path, resource $resource, array $config = [])
 * @method boolean write(string $path, string $contents, array $config = [])
 * @method boolean writeStream(string $path, resource $resource, array $config = [])
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 */
class FFile extends Component
{
    const TYPE_LOCAL = '';
    const TYPE_FTP = 'ftp';
    const TYPE_sFTP = 'sFtp';
    const TYPE_GOOGLE_DRIVE = 'google';
    const TYPE_AWS = 'aws';

    /**
     * @var \League\Flysystem\Config|array|string|null
     */
    public $config;
    /**
     * @var string|null
     */
    public $cache;
    /**
     * @var string
     */
    public $cacheKey = 'flysystem';
    /**
     * @var integer
     */
    public $cacheDuration = 3600;
    /**
     * @var string|null
     */
    public $replica;
    /**
     * @var \League\Flysystem\FilesystemInterface
     */
    protected $filesystem;
    public $path;
    public $type;
    public $host;
    public $port;
    public $username;
    public $password;
    public $ssl;
    public $timeout;
    public $root;
    public $permPrivate;
    public $permPublic;
    public $passive;
    public $transferMode;
    public $systemType;
    public $ignorePassiveAddress;
    public $resourceManually;
    public $utf8;

    //Files: https://github.com/creocoder/yii2-flysystem
    public static function createFile($filename, $content = null, $override = true, $fs = null) {
        $fs = self::FileSystem($fs);
        $exists = $fs->has($filename);
        if ($exists) {
            if ($override) {
                $fs->update($filename, $content);
                return true;
            }
            else
                return false;
        } else {
            $fs->write($filename, $content);
            return true;
        }
    }

    //Files: https://github.com/creocoder/yii2-flysystem
    public static function readFile($filename, $fs = null) {
        $fs = self::FileSystem($fs);
        $exists = $fs->has($filename);
        if ($exists) {
            $contents = Yii::$app->fs->read($filename);
            return $contents;
        } else {
            return '';
        }
    }

    //Files: https://github.com/creocoder/yii2-flysystem
    public static function deleteFile($filename, $fs = null) {
        $fs = self::FileSystem($fs);
        $exists = $fs->has($filename);
        if ($exists) {
            return Yii::$app->fs->delete($filename);
        } else {
            return false;
        }
    }

    public static function createDir($path, $fs = null) {
        $fs = self::FileSystem($fs);
        $fs->createDir($path);
    }

    public static function deleteDir($path, $fs = null) {
        $fs = self::FileSystem($fs);
        $fs->deleteDir($path);
    }

    public static function listContents($path = '', $recursive = true, $fs = null) {
        $fs = self::FileSystem($fs);
        $contents = $fs->listContents($path, $recursive);
        return $contents;
    }

    //Files: https://github.com/creocoder/yii2-flysystem
    public static function FileSystem($type = null, $host = '', $username = '', $password = '', $port = null, $rootFolder = '/', $ssl = true, $timeout = 60) {
        if (isset($type) && is_object($type)) {
            return $type;
        }

        if (empty($rootFolder))
            $rootFolder = '/';

        if (is_string($type) && !empty($type)) {
            if ($type == self::TYPE_FTP || $port == 21) {
                $fs = self::FTP($host, $username, $password, $port, $rootFolder, $ssl, $timeout);
                return $fs;
            } else if ($type == self::TYPE_sFTP || $port == 22) {
                $fs = self::sFTP($host, $username, $password, $port, $rootFolder, $ssl, $timeout);
                return $fs;
            }
        }

        if (isset(Yii::$app->fs))
            return Yii::$app->fs;

        return self::Local();
    }

    public static function Local($path = '@backend/..') {
        $fs = new LocalFilesystem();
        $fs->path = '@backend/..';
        $fs->init();
        return $fs;
    }

    public static function FTP($host = '', $username = '', $password = '', $port = null, $rootFolder = '/', $ssl = true, $timeout = 60) {
        $fs = isset(Yii::$app->ftpFs) ? Yii::$app->ftpFs : new FtpFilesystem();

        if (!empty($fs->host))
            return $fs;

        // 'port' => 21,
        // 'username' => 'your-username',
        // 'password' => 'your-password',
        // 'ssl' => true,
        // 'timeout' => 60,
        // 'root' => '/path/to/root',
        // 'permPrivate' => 0700,
        // 'permPublic' => 0744,
        // 'passive' => false,
        // 'transferMode' => FTP_TEXT,
        if (!isset($host))
            $host = FHtml::getApplicationConfig('FTP HOST');
        if (!isset($username))
            $username = FHtml::getApplicationConfig('FTP USERNAME');
        if (!isset($password))
            $password = FHtml::getApplicationConfig('FTP PASSWORD');
        if (!isset($port))
            $port = FHtml::getApplicationConfig('FTP PORT', 21);

        $fs->host = $host;
        $fs->port = $port;
        $fs->username = $username;
        $fs->password = $password;
        $fs->ssl = $ssl;
        $fs->timeout = $timeout;
        $fs->root = $rootFolder;
        $fs->init();
        return $fs;
    }

    public static function sFTP($host = '', $username = '', $password = '', $port = null, $rootFolder = '/', $ssl = true, $timeout = 60) {
        $fs = isset(Yii::$app->sftpFs) ? Yii::$app->sftpFs : new SftpFilesystem();

        if (!empty($fs->host))
            return $fs;

        if (!isset($host))
            $host = FHtml::getApplicationConfig('FTP HOST');
        if (!isset($username))
            $username = FHtml::getApplicationConfig('FTP USERNAME');
        if (!isset($password))
            $password = FHtml::getApplicationConfig('FTP PASSWORD');
        if (!isset($port))
            $port = FHtml::getApplicationConfig('FTP PORT', 22);

        $fs->host = $host;
        $fs->port = $port;
        $fs->username = $username;
        $fs->password = $password;
        $fs->timeout = $timeout;
        $fs->root = $rootFolder;
        $fs->init();
        return $fs;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {

    }

    /**
     * @return AdapterInterface
     */
    public function prepareAdapter() {
        return null;
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->filesystem, $method], $parameters);
    }
}
