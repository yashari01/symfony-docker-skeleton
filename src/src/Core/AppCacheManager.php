<?php


namespace App\Core;


use Symfony\Contracts\Cache\CacheInterface;

class AppCacheManager
{
    /**
     * @var CacheInterface
     */
    private $countriesCache;
    /**
     * @var CacheInterface
     */
    private $usersCache;

    public function __construct(
        CacheInterface $countriesCache,
        CacheInterface $usersCache
    )
    {
        $this->countriesCache = $countriesCache;
        $this->usersCache = $usersCache;
    }

    /**
     * @return CacheInterface
     */
    public function getCountriesCache(): CacheInterface
    {
        return $this->countriesCache;
    }

    /**
     * @param CacheInterface $countriesCache
     */
    public function setCountriesCache(CacheInterface $countriesCache): void
    {
        $this->countriesCache = $countriesCache;
    }

    /**
     * @return CacheInterface
     */
    public function getUsersCache(): CacheInterface
    {
        return $this->usersCache;
    }

    /**
     * @param CacheInterface $usersCache
     */
    public function setUsersCache(CacheInterface $usersCache): void
    {
        $this->usersCache = $usersCache;
    }
}